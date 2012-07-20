
package judge;

import java.io.*;
//import java.net.URL;

/**
 *
 * @author YONGSONG
 * To add in another language, just inherit from Executer
 */
public class TestExecutor extends Thread{
    
    //protected static String newLine= System.getProperty("line.separator");

    private String[] strExec;
    private String[] inputStr;
    
    private int numTests;
    private String[] runOutputs;
    
    //protected Runtime rt;
    private ProcessBuilder pb;
    private Process pc;
    
    public TestExecutor(String[] shell, String[] inputs){

        this.strExec = shell;
        this.inputStr = inputs;
        
        numTests = MainServlet.getNumTests();
        runOutputs=new String[numTests];
        
        // initialize runOutputs
        for(int i=0; i<numTests;i++){
            runOutputs[i]="";
        }
        
        pb = null;
    }
    
    @Override
    public void run(){
        PrintStream ps=null;
        BufferedReader bfr=null;
        StringBuilder sbOut=null;
        try{
            for(int i=0; i<numTests; i++){
                pb = new ProcessBuilder(strExec);
                pb.redirectErrorStream(true);
                pc = pb.start();

                // Outstream for passing data into the program
                if(inputStr[i]!=null && !inputStr[i].isEmpty()){
                    ps = new PrintStream(new BufferedOutputStream(pc.getOutputStream()));
                    ps.print(inputStr[i]);
                    ps.close();
                }

                sbOut = new StringBuilder();
                int ch;
                bfr = new BufferedReader(new InputStreamReader(pc.getInputStream()));
                while((ch=bfr.read())!= -1){
                    sbOut.append((char)ch);
                }

                pc.waitFor();            

                runOutputs[i] = sbOut.toString();
            } 
        }
        catch(IOException ex){
            
        }
        catch(InterruptedException ex){
            ex.printStackTrace();
        }
        finally{
            if(ps!=null ){
                ps.close();
            }
            if(bfr!=null){
                try{
                    bfr.close();
                }catch(IOException e){}
            }
        }
    
    }
    public String[] getRunOutput(){
        return runOutputs;
    }

    public void shutdown(){
        pc.destroy();
    }
    
}
