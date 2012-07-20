
package detect;

import java.util.ArrayList;
import java.util.regex.Pattern;
import java.util.regex.Matcher;

//import java.io.*;

/**
 * This class Read in a string, and generate two levels of tokens
 *
 * ReservedWords and AllWords are the two levels
 * 
 * @author YONGSONG
 */
public class LexicalParser {

    private String inputStr;
    
    //private Scanner sc;
    private ArrayList<Integer> reservedTokens;
    private ArrayList<Integer> allTokens;

    Matcher reservedMatcher;
    
    Matcher allMatcher;
    private ArrayList<Integer> starts;
    private ArrayList<Integer> ends;

    public LexicalParser(String input){
        this.inputStr = input;
        // Note the sequence are important
        String wordFinder = "([a-zA-Z][\\w]*)";//6
        String punctFinder = "([\\p{Punct}&&[^\"\'\\(\\)]]+)";//11
        reservedMatcher = Pattern.compile(wordFinder+"|"+punctFinder).matcher(inputStr);

        String strLiteralFinder ="(\"[((\\\\\")|([^\"]))&&[^\n]]*?\")";//4
        String charLiteralFinder = "(\'(.|(\\\\.))\')";//1
        String methodFinder = "([a-z][\\w]*\\s*\\()";//5
        String numLiteralFinder ="((\\+|-)?([0-9]+(\\.[0-9]+)?))";//7
        
        allMatcher = Pattern.compile(charLiteralFinder+"|"+ strLiteralFinder+"|"+methodFinder
           +"|"+wordFinder+"|"+numLiteralFinder+"|"+punctFinder).matcher(inputStr);

        //System.out.println(allMatcher.groupCount());
    }
    
    /**
     * Scans for keywords and operators only, and put results in tokens[0]
     */
    protected void scanReservedWords(){
        reservedTokens = new ArrayList<Integer>(128);

        String currToken;
        for(int i=0; reservedMatcher.find(); i++){
            currToken = reservedMatcher.group();

            if(reservedMatcher.group(1)!=null){ // a word is found
                if(SymbolTable.symbols[0].containsKey(currToken)){
                    this.reservedTokens.add(SymbolTable.symbols[0].get(currToken));
                }
            }
            else{  // in this case a punctuation mark is found
                if(SymbolTable.symbols[0].containsKey(currToken)){
                    this.reservedTokens.add(SymbolTable.symbols[0].get(currToken));
                }
                else if(currToken.length()>1){
                    // break the operators
                    for(int j=0; j< currToken.length(); j++){
                        String opr = ""+currToken.charAt(j);
                        if(SymbolTable.symbols[0].containsKey(opr)){
                            this.reservedTokens.add(SymbolTable.symbols[0].get(opr));
                        }
                    }
                }
            }
        }
    }
    
    public ArrayList<Integer> getReservedTokens(){
        scanReservedWords();
        return reservedTokens;
    }
    
    

    /**
     * Scans for all reserved words, class names, literals, methods
     */
    protected void scanAllWords(){
        allTokens = new ArrayList<Integer>(256);

        //initialize the starts and ends
        starts = new ArrayList<Integer>(256);
        ends = new ArrayList<Integer>(256);

        String currToken;
        int st, ed;
        for(int i=0; allMatcher.find(); i++){
            currToken = allMatcher.group();
            st = allMatcher.start();
            ed = allMatcher.end();

            //wordFinder, it could be a keyword or classname
            if(allMatcher.group(6)!=null){
                if(SymbolTable.symbols[0].containsKey(currToken)){
                    this.allTokens.add(SymbolTable.symbols[0].get(currToken)); //keyword
                    starts.add(st);
                    ends.add(ed);
                }
                else if(SymbolTable.symbols[1].containsKey(currToken)){
                    this.allTokens.add(SymbolTable.symbols[1].get(currToken));//classname
                    starts.add(st);
                    ends.add(ed);
                }
            }
            // punctuation mark finder
            else if(allMatcher.group(11)!=null){
                if(SymbolTable.symbols[0].containsKey(currToken)){
                    this.allTokens.add(SymbolTable.symbols[0].get(currToken));
                    starts.add(st);
                    ends.add(ed);
                }
                else if(currToken.length()>1){
                    // break the operators
                    for(int j=0; j< currToken.length(); j++){
                        String opr = ""+currToken.charAt(j);
                        if(SymbolTable.symbols[0].containsKey(opr)){
                            this.allTokens.add(SymbolTable.symbols[0].get(opr));
                            starts.add(st+j);
                            ends.add(st+j+1);
                        }
                    }
                }
            }
            // check whether it is a literal
            else if((allMatcher.group(1) != null) || (allMatcher.group(4) != null)
                    ||(allMatcher.group(7)!=null) ){
                if(SymbolTable.symbols[2].containsKey(currToken)){
                    this.allTokens.add(SymbolTable.symbols[2].get(currToken));
                }
                else{
                    SymbolTable.symbols[2].put(currToken, SymbolTable.nextLiteralValue);
                    this.allTokens.add(SymbolTable.nextLiteralValue);
                    SymbolTable.nextLiteralValue++;
                }
                starts.add(st);
                ends.add(ed);
            }
            // method finder
            else if(allMatcher.group(5)!=null){
                String name = currToken.substring(0, currToken.length()-1).trim();
                if(SymbolTable.symbols[3].containsKey(name)){
                    this.allTokens.add(SymbolTable.symbols[3].get(name));
                }
                else{
                    SymbolTable.symbols[3].put(name, SymbolTable.nextMethodValue);
                    this.allTokens.add(SymbolTable.nextMethodValue);
                    SymbolTable.nextMethodValue++;
                }
                starts.add(st);
                ends.add(ed);
            }
        }
    }
    
    
    public ArrayList<Integer> getAllTokens(){
        scanAllWords();
        return allTokens;
    }

    public ArrayList<Integer> getAllStarts(){
        return starts;
    }

    public ArrayList<Integer> getAllEnds(){
        return ends;
    }

//    public static void main(String[] args) throws Exception{
//        SymbolTable.loadSymbols();
//        SourceReader sr = new SourceReader(new File("E:\\Documents\\Study_in_NTU\\FYP\\codes\\FileTest.java"));
//        sr.readFile();
//        String input = sr.getCodeString();
//        LexicalParser lp = new LexicalParser(input);
//        lp.scanReservedWords();
//        lp.scanAllWords();
//    }
}
