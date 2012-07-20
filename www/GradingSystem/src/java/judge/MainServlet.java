
package judge;

import java.io.*;
import java.sql.*;
import javax.servlet.*;
import javax.servlet.http.*;

// main entry for Grading System
public class MainServlet extends HttpServlet {
    /**
     * File Root for Grading System on the server
     */
    protected static String gsRootPath;
    protected static String policyFileName;
//    /**
//     * The Path for Exception log file
//     */
//    private static String logFilePath;
    

    public static final int MAX_TEST_CASE= 10;
    private static int numTestCase;

    @Override
    public void init() throws ServletException{
        gsRootPath = this.getServletContext().getInitParameter("gsroot");
//        logFilePath = this.getServletContext().getInitParameter("logpath");
        
        // initialize the java execution policy file
        policyFileName = this.getServletContext().getInitParameter("javapolicy");
        
    }
    
    protected Connection getDbConn(){
        // initialize context parameter -  DbConn
        String conStr = this.getServletContext().getInitParameter("jdbcstr");
        String dbUser = this.getServletContext().getInitParameter("dbuser");
        String dbPassword = this.getServletContext().getInitParameter("dbpassword");
        
        Connection conn=null;
        try{
            // one thing to note is the timeout of DbListener.dbConn
            conn= DriverManager.getConnection(conStr, dbUser, dbPassword);
        }
        catch(SQLException ex){
            ex.printStackTrace();
        }
        finally{
            return conn;
        }
    }
    
    
    @Override
    public void doGet(HttpServletRequest request, HttpServletResponse response)
         throws IOException, ServletException {

        // Set the response MIME type of the response message
        response.setContentType("text/html");
        
        // Allocate a output writer to write the response message into the network socket
        PrintWriter out = response.getWriter();

        // Write the response message, in an HTML page
        out.println("<html>");
        out.println("<head><title>Online Grading System</title></head>");
        out.println("<body>");

        Connection conn = getDbConn();
        DbListener dbl = new DbListener(conn);
        
        String aid = request.getParameter("aid");
        if(!(aid==null || aid.equals(""))){
            
            int answerID = Integer.parseInt(aid);
            
            ResultSet rset;
            rset = dbl.getWaitingAnswer(answerID);
            
            try{
                // retrieve the entry for processing
                if(rset!=null && rset.next()){
                    String course = rset.getString("course_code");
                    String problem = rset.getString("p_name");
                    String username = rset.getString("username");
                    String language = rset.getString("lang");

                    String srcDir = gsRootPath +"/uploads/"+ course +"/"+ problem +"/"+ username;
                    String filename = rset.getString("filename");
                    
                    // compilation done here
                    SourceCompiler srcCompiler = new SourceCompiler(srcDir, filename, language, 
            dbl, answerID, out);
                    srcCompiler.compile();
                    
                    int pid = rset.getInt("p_id");

                    //DbListener dbIO = new DbListener();
                    String queryIO = "SELECT * FROM testcase WHERE p_id = " + pid;
                    ResultSet rst = dbl.queryDb(queryIO);
                    String inputString[]= new String[10];
                    String outputString[]= new String[10];
                    for(numTestCase=0; rst.next(); numTestCase++){
                        inputString[numTestCase] = rst.getString("inputs");
                        outputString[numTestCase] = rst.getString("outputs");
                        
                    }
                    
                    // input output should be read out from database, and hold using input[10], output[10]
                    AnswerJudge judge = new AnswerJudge
                            (answerID, srcCompiler.getBinFile(), inputString, outputString, dbl, out);
                    out.println("Start Executing...<br>");
                    judge.executeTests();

                    out.println("Executing finished...<br>");
                }
                else{
                    out.println("No entry found for executing.");
                }
            }
            catch(SQLException ex){
                ex.printStackTrace();
            }
        }
        else{
            out.println("No answer parameter provided.");
            out.println("<br>Stopping...");
        }
        
        out.println("<body></html>");
        out.close();
   }
    
   // this is for post
    @Override
    public void doPost(HttpServletRequest request, HttpServletResponse response)
         throws IOException, ServletException {
        this.doGet(request, response);
    }
    
   
    public static int getNumTests(){
        return numTestCase;
    }

    
    // main is only for testing and debugging
    public static void main(String[] args){
        gsRootPath = "/var/gradingsystem/";
        
        // initialize the java execution policy file
        policyFileName = "/var/gradingsystem/gspolicy";
        
        DbListener dbl=null;
        try{
            dbl = new DbListener(DriverManager.getConnection("jdbc:mysql://localhost:3306/gradingsystem", 
                    "gsadmin", "ntu2011"));
        }
        catch(SQLException ex){
            ex.printStackTrace();
        }
        
        int answerID = 153;
        
        ResultSet rset;
        rset = dbl.getWaitingAnswer(answerID);
        try{
            // retrieve the entry for processing
                if(rset!=null && rset.next()){
                    String course = rset.getString("course_code");
                    String problem = rset.getString("p_name");
                    String username = rset.getString("username");
                    String language = rset.getString("lang");

                    String srcDir = gsRootPath +"/uploads/"+ course +"/"+ problem +"/"+ username;
                    String filename = rset.getString("filename");
                    
                    // compilation done here
                    SourceCompiler srcCompiler = new SourceCompiler(srcDir, filename, language, 
            dbl, answerID, null);
                    srcCompiler.compile();
                    
                    int pid = rset.getInt("p_id");

                    //DbListener dbIO = new DbListener();
                    String queryIO = "SELECT * FROM testcase WHERE p_id = " + pid;
                    ResultSet rst = dbl.queryDb(queryIO);
                    String inputString[]= new String[10];
                    String outputString[]= new String[10];
                    for(numTestCase=0; rst.next(); numTestCase++){
                        inputString[numTestCase] = rst.getString("inputs");
                        outputString[numTestCase] = rst.getString("outputs");
                        
                    }
                    
                    // input output should be read out from database, and hold using input[10], output[10]
                    AnswerJudge judge = new AnswerJudge
                            (answerID, srcCompiler.getBinFile(), inputString, outputString, dbl, null);
                    System.out.println("Start Executing...<br>");
                    judge.executeTests();

                    System.out.println("Executing finished...<br>");
                }
                else{
                    System.out.println("No entry found for executing.");
                }
        }
        catch(SQLException ex){
            ex.printStackTrace();
        }
    }
    
}