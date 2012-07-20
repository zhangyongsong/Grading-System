
package judge;

import java.io.*;
/**
 *
 * @author YONGSONG
 */
public class CppExec extends TestExecutor{
   public CppExec(String srcDir, int anID, DbListener dbl,  String srcName, String[] input, PrintWriter pw){
        super(srcDir, anID, dbl, srcName, input, pw);
        this.binFile =new File(sourceDirectory+"/main.exe");
    }

   @Override
   public boolean srcCompiler() throws IOException{
        String cppFile =  sourceDirectory + "/" +srcFileName;
        String outFile = binFile.getAbsolutePath();

        String strCompile[] ={"g++",
                               cppFile,
                               "-o",
                               outFile
        };
        if(binFile.exists())
            binFile.delete();
        
        pb = new ProcessBuilder(strCompile);
        pc = pb.start();
        
        dbl.updateStatus(answerID, "Compiling");
        
        try{
            // the probability of Exception - nearly zero
            pc.waitFor();
        }
        catch(InterruptedException ex){
            out.println("Compiling Process interrupted...");
            ex.printStackTrace(out);
            
        }

        if(binFile.exists())
            return true;
        else return false;
   }
   
   @Override
   public boolean binRunner() throws Exception{
        if(!binFile.exists()){
            return false;
        }
        String strExec[] =  {binFile.getAbsolutePath()};

        dbl.updateStatus(answerID, "Running");
        for(int i=0; i<MainServlet.getNumTests(); i++){
            pb = new ProcessBuilder(strExec);
            pc = pb.start();
            
            // Outstream for passing data into the program
            if(inputStr[i]!=null && !inputStr[i].isEmpty()){
                PrintStream ps = new PrintStream(new BufferedOutputStream(pc.getOutputStream()));
                ps.print(inputStr[i]);
                ps.close();
            }

            StringBuilder sbOut = new StringBuilder();
            int ch;
            BufferedReader bfr = new BufferedReader(new InputStreamReader(pc.getInputStream()));
            while((ch=bfr.read())!= -1){
                sbOut.append((char)ch);
            }

            pc.waitFor();
            
            runOutput[i] = sbOut.toString();
        }
        return true;
    }

    @Override
    public void run(){
        try{
            binRunner();
        }
        catch(Exception ex) {
            ex.printStackTrace(out);
            //MainServlet.writeLog(answerID, "Exception in execution of Java source code.");
            try{
                binRunner();
            }
            catch(Exception err){
                ex.printStackTrace(out);
            }
        }
    }
  
}