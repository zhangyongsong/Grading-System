
package detect;

import java.io.File;
import java.io.FileReader;
import java.io.BufferedReader;
import java.io.IOException;

/**
 * This class reads in the content of the java source code,
 * and put the content in a String.
 *
 * @author YONGSONG
 */
public class SourceReader {

    private File sourceFile;
    private String strCode;

    public SourceReader(File inputPath){
        this.sourceFile=inputPath;
        this.strCode="";
    }
    /**
     * Read in the source code into strCode
     *
     * @throws IOException, if the file is not read properly,
     * Exception should be handled during processing
     */
    public void readFile() throws IOException{
        StringBuilder sb = new StringBuilder("");
        
        BufferedReader bReader;
        int in;

        bReader = new BufferedReader(new FileReader(sourceFile));

        while((in=bReader.read())!= -1){
            sb.append((char)in);
        }

        strCode = sb.toString();
        bReader.close();
    }

    public String getCodeString(){
        return strCode;
    }

}
