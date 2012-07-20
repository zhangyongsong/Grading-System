
package judge;

import java.io.*;

/**
 *
 * @author YONGSONG
 */
public class AnswerJudge{
    private int answerID;
    private String[] strShell;
    
    /**testcase input & output in the database */
    private String[] inputs;
    private String[] outputs;
    private DbListener dbl;
    
    // for debugging
    private PrintWriter out;
    private boolean compiled;
    
    private static final int FIVE_SECOND = 5000;
    
    public AnswerJudge(int anID, File binFile, String[] inputs, String[] outputs, 
            DbListener dbListener, PrintWriter pw){
        this.answerID = anID;
        dbl = dbListener;
        out = pw;
        
        if(binFile.exists()){
            compiled = true;
        }
        else compiled = false;
        if(compiled){
            String srcDir = binFile.getParent();
            String filename = binFile.getName();
            if(filename.toLowerCase().endsWith(".class")){
                // java source code
                String baseName =  filename.substring(0, filename.lastIndexOf("."));
                
                String[] javaShell = {"java",
                                "-Djava.security.manager",
                                "-Djava.security.policy=="+ MainServlet.policyFileName,
                                "-classpath",srcDir,
                                baseName
                                };
                strShell = javaShell;
            }
            else{
                String[] cppShell  =  {binFile.getAbsolutePath()};
                strShell = cppShell;
            }

            this.inputs = inputs;
            this.outputs = outputs;
        }
    }

    
    public void executeTests(){
        String exResult="";
        if(compiled){
            TestExecutor testExec = new TestExecutor(strShell, inputs);
            
            long start = System.currentTimeMillis();
            testExec.start();

            long end = start + FIVE_SECOND;
            do{
                try{
                    Thread.sleep(50);
                }
                catch(InterruptedException ex){
                    ex.printStackTrace(out);
                }
                
                if(System.currentTimeMillis() > end){
                    testExec.shutdown();
                    exResult = "No - Time Limit Exceeded";
                }
            } while(testExec.getState().toString().equals("RUNNABLE"));


            if(exResult.isEmpty()){
                String[] runOutputs = testExec.getRunOutput();
                for(int i=0; i<runOutputs.length; i++){
                    if(runOutputs[i].isEmpty()){
                        exResult = "No - Runtime Error";
                        break;
                    }
                    else if(checkOutput(runOutputs[i],outputs[i])==false){
                        exResult = "No - Wrong Result";
                        break;
                    }
                }
                if(exResult.isEmpty()){
                    exResult="Yes";
                }
            }
        }
        else exResult="No - Compilation Error";

        String queryAnswer = "UPDATE gradingsystem.answer SET ex_status = \'Complete\'"
            +", status = \'"+exResult+"\'  where answer.an_id = "+ answerID;
        System.out.println(queryAnswer+"<br/>");
        
        out.println(queryAnswer+"<br/>");
        dbl.updateAnswer(queryAnswer);
        
    }
    
    private boolean checkOutput(String actualOutput, String testcaseOutput){
        String[] testTokens = testcaseOutput.split("\\s");
        boolean allNumeric=true;
        for(String token:testTokens){
            allNumeric= allNumeric && isNumeric(token);
        }
        
        boolean result =true;
        if(allNumeric){
            String[] actualTokens = actualOutput.split("\\s");
            for(int i=0; i<testTokens.length; i++){
                result = result && checkNumber(actualTokens[i], testTokens[i]);
            }
            return result;
            
        }
        else{
            // check by line
            String[] actualLines=rtrim(actualOutput).split("\r\n|\r|\n");
            String[] testLines = rtrim(testcaseOutput).split("\r\n|\r|\n");
            
            if(actualLines.length!= testLines.length){
                return false;
            }
            for(int i=0; i< testLines.length; i++){
                result = result && checkLine(actualLines[i], testLines[i]);
            }
            return result;
        }
        
    }
    private boolean checkNumber(String actual, String test){
        double actualNum = Double.parseDouble(actual);
        double testNum = Double.parseDouble(test);
        if(actualNum== testNum){
            return true;
        }
        else return false;
    }

    private boolean checkLine(String actual, String test){
        if(isNumeric(test)){
            return checkNumber(actual, test);
        }
         else{
            if(rtrim(actual).equals(rtrim(test))){
                return true;
            }
            else return false;
         }
    }
    
    
    public static boolean isNumeric(String str){  
        try {  
            double d = Double.parseDouble(str);  
        }  
        catch(NumberFormatException nfe){  
            return false;  
        }  
        return true;  
    }
    
    public static String rtrim(String str){
       if(str==null)
           return null;
       
       int index = str.length();
        while (index > 0 && Character.isWhitespace(str.charAt(index-1)))
            index--;
        
        // at here charAt(index) is not whitespace
        // index == 0 means the entire string is white space; substring will return the empty string  
        str = str.substring(0, index);
        return str;
    }
}
