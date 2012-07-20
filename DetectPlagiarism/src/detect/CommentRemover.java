
package detect;

/**
 * This class is used for removing comments in java
 *
 * @author YONGSONG
 */
public class CommentRemover {
    private boolean inSlashComment=false;
    private boolean inMultiLineComment=false;
    private boolean inSingleQuote=false;
    private boolean inDoubleQuote=false;

    private String inputStr;
    private char prevChar;
    private char currChar;
    private StringBuilder outputSb;

    public CommentRemover(String input){
        inputStr=input;
        outputSb=new StringBuilder("");
        prevChar = '\0';
    }

    public void removeComment(){
        for(int i=0; i< inputStr.length(); i++){
            currChar = inputStr.charAt(i);
            readChar();
        }
    }

    /**
     * Analyzes next character, and determine whether to append or not
     *
     * If the character is not readable (Comments), a \0 would be append instead
     *
     */
    protected void readChar(){
        boolean readable = false;
        if(inSlashComment){
            if (currChar=='\n')
                inSlashComment = false;
        }
        else if(inMultiLineComment ){
            if((prevChar == '*') && (currChar == '/'))
                inMultiLineComment = false;
        }
        else {
            readable = true;
            if(inSingleQuote){
                if((prevChar!='\\') && (currChar =='\''))
                    inSingleQuote=false;
            }
            else if(inDoubleQuote){
               if((prevChar != '\\') && (currChar == '\"'))
                inDoubleQuote=false;
            }
            else {
                if(prevChar == '/'){
                    if(currChar=='*'){
                        readable =false;
                        inMultiLineComment=true;
                        outputSb.deleteCharAt(outputSb.length()-1);
                        outputSb.append('\0');
                    }
                    if(currChar=='/'){
                        readable=false;
                        inSlashComment=true;
                        outputSb.deleteCharAt(outputSb.length()-1);
                        outputSb.append('\0');
                    }
                }
                else if(currChar =='\'')
                    inSingleQuote=true;
                else if(currChar =='\"')
                    inDoubleQuote=true;
            }
        }
        if(readable)
            outputSb.append(currChar);
        else
            outputSb.append('\0'); // note a \0 is append for replacing the position
        prevChar = currChar;
    }

    public StringBuilder getReplacedStringBuilder(){
        return outputSb;
    }
}
