
package detect;

import java.util.Scanner;
import java.util.regex.Pattern;
import java.util.regex.Matcher;

/**
 * The main purpose of this class is to check the method.
 * If the method is not used, then it is replaced with "\0" String of equal length.
 *
 * @author YONGSONG
 */
public class FunctionChecker {
    private StringBuilder inputSb;
    private StringBuilder outputSb;
    
    /** 
     * The number of function is expected less than MAX_FUN
     */
    private static final int MAX_FUN = 20;

    private String[] functions;

    // bounds record the start and end for the function (java type end)
    private int[][] bounds;
    private boolean[] isCalled;
    private int count;

    public FunctionChecker(StringBuilder input){
        this.inputSb = input;

        this.functions = new String[MAX_FUN];
        this.bounds = new int[2][MAX_FUN];
        this.isCalled= new boolean[MAX_FUN];
        count =0;

    }
    
    protected void functionFinder(){

        String functionPattern =
          "(\\}|;)\\s+((public)|(protected)|(private)|())\\s+(static\\s+)?"  //this is the "public static"
          +"[\\w^\\d]+\\s+[\\w]+\\s*"   // this corresponds to "int functionName"
          +"\\([\\w\\[,\\]\\s]*\\)\\s*"    // this is inside the bracket "()"
          +"(throws)?(\\s|\\w|,)*"// for Exception concern
          +"\\{"; // the function detection ends at the opening bracket

        //System.out.println(functionPattern);
        Pattern p=Pattern.compile(functionPattern);
        Matcher m = p.matcher(inputSb);

        String header;
        while(m.find()){
            header = inputSb.substring(m.start()+1, m.end());
            functions[count] = getFunctionName(header);
            bounds[0][count]= m.start()+1;
            bounds[1][count]=getFunctionEnd(m.end());

//            System.out.println("Function Name: "+functions[count]);
//            System.out.println("Function Starts: "+bounds[0][count]+", Ends:"+ bounds[1][count]);
//            System.out.println(inputStr.substring(bounds[0][count], bounds[1][count]));

            this.count++;
        }
    }

    protected String getFunctionName(String header){
        header = header.replace("(", " ( ");
        Scanner sc = new Scanner(header);

        String prevToken="";
        String currToken;

        while(sc.hasNext()){
            currToken = sc.next();
            if(currToken.equals("(")){
                return prevToken;
            }
            prevToken = currToken;
        }
        return "";
    }
    
    protected int getFunctionEnd(int rawEnd){
        int braceLevel =1;
        char ch;
        for(int i=rawEnd; i<inputSb.length(); i++){
            ch = inputSb.charAt(i);
            if(ch=='{'){
                braceLevel++;
            }
            else if(ch=='}'){
                braceLevel--;
            }

            if(braceLevel==0){
                return i+1; // the end is outside of substring
            }
        }
        return -1; // error in source file
    }

    protected void checkForCalling(){
        for(int i=0; i< count; i++){
            isCalled[i] = false;

            if(functions[i].equals("main")){
                isCalled[i]=true;
                continue;
            }
            Pattern namePattern = Pattern.compile(this.functions[i]);
            Matcher match = namePattern.matcher(inputSb);


            while(match.find()){//function name is found
                int start = match.start();
                if(start<bounds[0][i] || start>bounds[1][i]){
                    // the function name is outside current definition
                    isCalled[i]=true;
                    break;
                }
            }
        }
    }

    protected void RemovedUnused(){
        outputSb = new StringBuilder(inputSb);
        // for removing unused function, should start from the end of the source file
        char ch = '\0';
        for(int i= count-1; i>=0; i--){
            if(isCalled[i] == false){
                for(int j=bounds[0][i]; j< bounds[1][i]; j++){
                    outputSb.setCharAt(j, ch);
                }
            }
        }
    }


    // replace some operator and surround operators with space for delimeter
    protected void handleOperators(){

        // surround later operators and special symbol with space
        StringBuilder sb = new StringBuilder();
        for(int i=0; i< outputSb.length(); i++){
            char ch = outputSb.charAt(i);
            if(Character.isLetterOrDigit(ch) || ch=='_'|| ch=='$'|| Character.isWhitespace(ch) || ch=='\0')
                sb.append(ch);
            else{
                sb.append('\1');
                sb.append(ch);
                sb.append('\1');
            }
        }
        outputSb=sb;
    }

    public void checkFunctions(){
        this.functionFinder();
        this.checkForCalling();
        this.RemovedUnused();

        //this.handleOperators();
        // handle operator is not used, as operators should be dealed with by regex
    }

    /**
     * This is the function for retrieve outputString
     * @return Output String of checking functions
     */
    public StringBuilder getOutputSb(){
        return this.outputSb;
    }

//    public static void main(String[] args) throws IOException{
//        String input = readFile();
//        FunctionChecker fc = new FunctionChecker(input);
//        fc.checkFunctions();
//        System.out.println(fc.getOutputString().replaceAll("\0",""));
//    }
}
