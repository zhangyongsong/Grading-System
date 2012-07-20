
package judge;

import java.io.*;
/**
 *
 * @author YONGSONG
 */
public class JavaExec extends TestExecutor{
    protected static String policyFileName;
    private String baseName;
    
    public JavaExec (String srcDir, int anID, DbListener dbl, String srcName, String[] input, PrintWriter pw){
        super(srcDir, anID, dbl, srcName, input, pw);
        baseName = srcName.substring(0, srcName.lastIndexOf("."));
        this.binFile =new File(sourceDirectory+"/"+ baseName+".class");
    }

//    /**
//     * Java Compile method
//     * Here it will put the class file as the same directory with the source file
//     */
//    @Override
//    public boolean srcCompiler() throws IOException{
//        String javaFile = sourceDirectory + "/" +srcFileName ;
//        String destination = sourceDirectory ;
//        String strCompile[] = {"javac",
//                                "-d",
//                                destination,
//                                javaFile};
//        //String strCompile = "javac -d " + destination + " " + javaFile;
//        //System.out.println(strCompile);
//        if(binFile.exists())
//            binFile.delete();
//           
//        pb = new ProcessBuilder(strCompile);
//        
//        pc = pb.start();
//
//        dbl.updateStatus(answerID, "Compiling");
//        
//        try{
//            // the probability of Exception - nearly zero
//            pc.waitFor();
//        }
//        catch(InterruptedException ex){
//            out.println("Interrupted Exception: Compiling Process interrupted...");
//            ex.printStackTrace(out);
//        }
//        
//        if(binFile.exists())
//            return true;
//        else return false;
//    }
    
    @Override
    public boolean binRunner() throws Exception{
        if(!binFile.exists()){
            return false;
        }
//            String strExec =  "java -Xmx20m -Djava.security.manager -Djava.security.policy=="
//                    +  this.getFileRoot()+ policyFileName
//                    + " -classpath " + "\""+ sourceDirectory +"\" "+ baseName;
        String strExec[] = {"java",
                            "-Djava.security.manager",
                            "-Djava.security.policy=="+ policyFileName,
                            "-classpath",
                            sourceDirectory,
                            baseName};

        dbl.updateStatus(answerID, "Running");
        for(int i=0; i<MainServlet.getNumTests(); i++){
            pb = new ProcessBuilder(strExec);
            
            runOutput[i] = sbOut.toString();
        }
        return true;
    }
    
    @Override
    public void run(){
        try{
            binRunner();
            out.println("Test");
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
