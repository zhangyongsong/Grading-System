
package detect;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;
import java.util.HashMap;

/**
 * This class set reserved words
 *
 * @author YONGSONG
 */
public class SymbolTable {

    /**
     * symbols is a hashmap array for holding symbol tables
     * 
     * For symbols, symbols[0] is reserved words, symbols[1] is Class Names,
     * symbols[2] is literals, symbols[3] is methods
     */
    protected static HashMap<String,Integer>[] symbols
            = new HashMap[4];
    
    protected static int nextLiteralValue = Main.INIT_LITERAL;
    protected static int nextMethodValue = Main.INIT_METHOD;

    public static void loadSymbols(){
        System.out.println("Loading symbol tables...");
        initReservedWords();
        initClassNames();

        // symbols[2] is for literals(numbers), symbols[3] is for methods
        symbols[2] = new HashMap<String, Integer>(250);
        symbols[3] = new HashMap<String, Integer>(250);
    }

    public static void initReservedWords(){
        symbols[0] = new HashMap<String, Integer>(100);
        
        // put all SymbolTable words and operators into HashMap
        initFromFile(Main.RESERVED_NAME_FILE, symbols[0], 0);
        
    }

    /**
     * This class would read in Class Names from CLASS_FILE
     * and initiate classNames with values
     */
    public static void initClassNames(){
        symbols[1] = new HashMap<String, Integer>(250);
        initFromFile(Main.CLASS_NAME_FILE, symbols[1], Main.INIT_CLASS);
    }

    protected static void initFromFile(String filePath, HashMap<String,Integer> hash, int nextValue){
        File inputFile = new File(filePath);

        BufferedReader bf=null;
        try{
            bf = new BufferedReader(new FileReader(inputFile));
            String line;
            while((line=bf.readLine())!=null){
                line=line.trim();
                if((!line.equals("") )&& line.indexOf("#")==-1){
                    hash.put(line, nextValue);
                    nextValue++;
                }
            }
        }
        catch(IOException ex){
            System.err.println(ex.getMessage());
            ex.printStackTrace();
        }
        finally{
            try{
                bf.close();
                if(bf!=null){
                    bf=null;
                }
            }
            catch(IOException e){}
        }
    }
}
