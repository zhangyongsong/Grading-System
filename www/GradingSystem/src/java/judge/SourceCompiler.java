
package judge;

import java.io.File;
import java.io.IOException;
import java.io.PrintWriter;

/**
 *
 * @author Yongsong
 */
public class SourceCompiler {
    // inputs needed
    private String language;
    private String srcDir;
    private String srcFilePath;
    
    private File binFile;
    
    private ProcessBuilder pb;
    private Process pc;
    
    private int answerID;
    private DbListener dbl;
    private PrintWriter out;
    
    public SourceCompiler(String sourceDir, String filename, String lang, 
            DbListener listener, int anID, PrintWriter pw){
        language = lang;
        srcDir = sourceDir;
        srcFilePath = srcDir+"/"+ filename;
        
        if(language.equals("Java")){
            String baseName =  filename.substring(0, filename.lastIndexOf("."));
            this.binFile =new File(srcDir+"/"+ baseName+".class");
        }
        else{
            this.binFile=new File(srcDir+"/main.exe");
        }
        if(binFile.exists()){
            binFile.delete();
        }
        
        this.dbl= listener;
        this.answerID = anID;
        this.out = pw;
        
        this.pb = null;
        this.pc = null;
    }
    
    public void compile(){
        dbl.updateStatus(answerID, "Compiling");
        
        try{
            if(language.equals("Java")){
                compileJava();
            }
            else{
                compileCpp();
            }
        }catch(IOException ex){
            ex.printStackTrace(out);
        }
        catch(InterruptedException ex){
            out.println("Interrupted Exception: Compiling Process interrupted...");
            ex.printStackTrace(out);
        }
        
    }
    
    private void compileJava() throws IOException, InterruptedException{
        String strCompile[] = {"javac",
                                "-d",
                                srcDir,
                                srcFilePath};
        
        pb = new ProcessBuilder(strCompile);
        
        pc = pb.start();
        pc.waitFor();
    }
    
    private void compileCpp()throws IOException, InterruptedException{
        
        String strCompile[] ={"g++",
                               srcFilePath,
                               "-o",
                               binFile.getAbsolutePath()
        };
        
        pb = new ProcessBuilder(strCompile);
        pc = pb.start();
        pc.waitFor();
        
    }
    
    public File getBinFile(){
        return this.binFile;
    }
}
