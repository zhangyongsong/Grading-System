
package detect;

import java.io.*;
import java.sql.*;
//import java.net.URI;

/**
 * Entry of Project
 *
 * @author YONGSONG
 */
public class Main {
    /* *******************************************
     * Package Constants
     *********************************************/
    // set the working directory before running
    private static final String WORKDIR = "/var/www/detect";
    //private static final String FOLDER = WORKDIR + "/TestCode/";

    protected static final String RESERVED_NAME_FILE =
        WORKDIR + "/Symbols/ReservedNames.txt";
    protected static final String CLASS_NAME_FILE =
        WORKDIR + "/Symbols/ClassNames.txt";
    
    // database info
    private static final String DBCONN="jdbc:mysql://localhost:3306/gradingsystem";
    private static final String USER = "gsadmin";
    private static final String PWD = "ntu2011";
    
    private static final String SOURCE_ROOT= "/var/gradingsystem/uploads";
    
    // parameters for interface between methods
    private static String[] sourceId=null;
    private static String[] originalSources = null;
    private static String[] modifiedSources=null;

    /* *******************************
     * Program configuration
     *********************************/
    protected static final int INIT_CLASS = 100;
    protected static final int INIT_LITERAL = 1000;
    protected static final int INIT_METHOD = 2500;
    protected static final int RADIX = 4096;

    protected static double RESERVED_THRESHOLD = 0.40;
    protected static double CUTOFF = 0.30;
    
    private static Cluster[] clustering;
    private static String[][][] strResults;
    private static String[][] strTexts;

    public static void main(String[] args){
        File[] sourceFiles;
        int pid;
        if(args.length>0){
            pid = Integer.parseInt(args[0]);
            sourceFiles = getSourceFiles(pid);
        }
        else{
            System.out.println("No problem passed in. Exiting...");
            return;
        }
        System.out.println("Detection started for problem "+ pid+"...");
        // the preprocessing of files: replace comments and remove unused functions
        Main.handleSources(sourceFiles);

        // load in different symbol library
        SymbolTable.loadSymbols();

        Main.compareTokens();

        generateReport();
        System.out.println("Terminating...");
    }

    public static File[] getSourceFiles(int problemId){
        Connection conn;
        String course="", pName ="";
        File[] files=null;
        try{
            conn = DriverManager.getConnection(DBCONN, USER, PWD);
            Statement stmt = conn.createStatement();
            String pQuery = "SELECT course_code, p_name FROM problem WHERE p_id = "+problemId;
            ResultSet prs = stmt.executeQuery(pQuery);
            if(prs!=null && prs.next()){
                course = prs.getString("course_code");
                pName = prs.getString("p_name");
            }
            
            String anQuery = "SELECT username, filename FROM answer WHERE p_id = "+ problemId
                +" AND isLatest=\"true\" AND ex_status=\"complete\" AND status =\"Yes\"";
            ResultSet anRs = stmt.executeQuery(anQuery);
            String fBase =SOURCE_ROOT+"/"+course+"/"+pName;
            anRs.last();
            files = new File[anRs.getRow()];
            sourceId = new String[anRs.getRow()];
            anRs.beforeFirst();
            
            while(anRs.next()){
                String username = anRs.getString("username");
                String filename = anRs.getString("filename");
                String path = fBase+"/"+username+"/"+filename;
                
                sourceId[anRs.getRow()-1] = username;
                files[anRs.getRow()-1]= new File(path);
            }
            conn.close();
        }
        catch(SQLException ex){
            ex.printStackTrace();
        }
        finally{
            return files;
        }
    }
    
    
    public static void handleSources(File[] files){
        //File[] files = new File(FOLDER).listFiles(new JavaSourceFilter());
        //sourceId = new String[files.length];
        
        originalSources = new String[files.length];
        modifiedSources = new String[files.length];

        for(int i=0; i<files.length; i++){
            File srcFile = files[i];
            if(srcFile.isFile()){
                //String srcPath =  FOLDER +"/"+ srcFile.getName();
                //String parsedPath = outputFolder.getAbsolutePath()+"/"+srcFile.getName()+".txt";

                SourceReader handler = new SourceReader(srcFile);
                FunctionChecker fchecker=null;
                //sourceId[i]=srcFile.getName();
                try{
                    handler.readFile();
                    originalSources[i]= handler.getCodeString();

                    CommentRemover cr = new CommentRemover(originalSources[i]);
                    cr.removeComment();
                    fchecker = new FunctionChecker(cr.getReplacedStringBuilder());
                    fchecker.checkFunctions();
                }
                catch(IOException ex){
                    System.err.println("File Reading Failed...");
                    ex.printStackTrace();
                }
                finally{
                    modifiedSources[i]=fchecker.getOutputSb().toString();
                }
            }
        }
    }

    public static void compareTokens(){
        HierarchicalComparison hcmp;
        WeightedMajorClustering majorClust;

        hcmp = new HierarchicalComparison(sourceId,
                originalSources, modifiedSources);
        
        System.out.println("Hierarchical Comparison Started...");
        hcmp.pairComparison();
        majorClust = new WeightedMajorClustering
                (hcmp.getClusters(),hcmp.getEdgeWeights());
        majorClust.wMajorClust();

        clustering=majorClust.getClusters();

        strResults = hcmp.getOutputs();
        strTexts = hcmp.getMarkedTexts();
    }


    protected static void generateReport(){
        File output = new File(WORKDIR+"/output/");
        if(output.exists()){
            for(File file:output.listFiles())
                file.delete();
        }
        else{
            output.mkdir();
        }
        
        System.out.println("Generating Report file...");
        File reportFile = new File(WORKDIR+"/output/report.html");
        String head="<html><head><title>Report</title>"+
           "<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/style.css\"></link></head><body>";
        
        head+="<h2>Comparisons result:</h2>"+"<pre>";
        String foot = "</pre><br>"+"Comparisons finished."+"</body></html>";
        StringBuilder body = new StringBuilder("");

        for(Cluster clust: clustering){
            if(clust==null)
                continue;
            // loop through each cluster to retrieve the report
            int[] arrClust = clust.getElements();
            body.append("<div class=\"cluster\"><b>Cluster:</b> ");
            for(int k=0; k< arrClust.length;k++ ){
                body.append(sourceId[arrClust[k]]);
                body.append("   ");
            }
            body.append("</div>\n");

            String[] result;
            body.append("<table class=\"report\"><tr><th>PairWise Comparisons<th>Rough Detection<th>Similarities<tr>");
            for(int i=0; i< arrClust.length; i++){
                for(int j=i+1; j< arrClust.length; j++){
                    
                    result = (arrClust[i]<arrClust[j])?(strResults[arrClust[i]][arrClust[j]])
                            :(strResults[arrClust[j]][arrClust[i]]);
                    
                    //System.out.println(arrClust[i]+":"+arrClust[j]);
                    String row =
                  "<tr><td>"+result[0]+"<td>"+result[1]+"<td>"+result[2]+"</tr>";
                    body.append(row);
                }
            }
           body.append("</table>");
        }
        PrintWriter pw=null;
        try{
            pw= new PrintWriter(new BufferedWriter(new FileWriter(reportFile)));
            pw.print(head + body + foot);
            pw.close();
            
            String fileName;
            String content;
            for(int i=0; i< strTexts.length; i++){
                for(int j=i+1; j< strTexts.length; j++){
                    if(strTexts[i][j]!=null && !strTexts[i][j].equals("")){
                        content="<html><head><title>"+sourceId[i]+" VS "+sourceId[j]+"</title>"+
           "<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/style.css\"></link></head><body>";
                        
                        //content += "<pre>"+strResults[i][j]+"</pre><br/>";
                        content += strTexts[i][j]+"</body></html>";
                        
                        fileName = WORKDIR+ "/output/"+sourceId[i]+"-"+sourceId[j]+".html";
                        pw = new PrintWriter(new BufferedWriter(new FileWriter(fileName)));
                        pw.print(content);
                        pw.close();
                    }
                }
            }
//            URI uri = reportFile.toURI();
//            java.awt.Desktop.getDesktop().browse(uri);
        }
        catch(IOException ex){
            ex.printStackTrace();
        }
        finally{
            pw.close();
        }
    }
}

class JavaSourceFilter implements FileFilter{
    @Override
    public boolean accept(File pathname){
        if(pathname.getName().toLowerCase().endsWith(".java")){
            return true;
        }
        else return false;
    }
}
