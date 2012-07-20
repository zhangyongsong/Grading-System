
package judge;

import java.sql.*;

/**
 * @author YONGSONG
 */
public class DbListener {
    // database connection, initialized in MainServlet
    private Connection dbConn;

    // an empty constructor
    public DbListener(Connection conn){
        dbConn = conn;
    }

    public ResultSet getWaitingAnswer(int answerID){
        String queryAnswer = "SELECT problem.course_code, problem.p_name, problem.lang, answer.* FROM answer INNER JOIN problem on answer.p_id = problem.p_id "
                +"WHERE an_id = "+ answerID;// +" and ex_status = \'Waiting\' AND isLatest = \'true\' ";
        try{
            Statement stmt = dbConn.createStatement();
            ResultSet rs = stmt.executeQuery(queryAnswer);
            return rs;
        }catch(SQLException ex){
            ex.printStackTrace();
            return null;
        }
    }

    public int updateStatus(int answerID, String ex_status){
        String updateQuery = "UPDATE gradingsystem.answer SET ex_status = \'" + ex_status
                + "\'  WHERE answer.an_id = "+ answerID;
        try{
            Statement stmt = dbConn.createStatement();
            int result = stmt.executeUpdate(updateQuery);
            return result;
           }catch(SQLException ex){
               ex.printStackTrace();
               //MainServlet.writeLog(answerID, "Update execution status in database failed.");
               return 0;
           }
    }


    public ResultSet queryDb(String query){
       try{
        Statement stmt = dbConn.createStatement();
        return stmt.executeQuery(query);
        }catch(SQLException ex){
            ex.printStackTrace();
            return null;
        }
    }

    public int updateAnswer(String query){
        try{
            Statement stmt = dbConn.createStatement();
            int result = stmt.executeUpdate(query);
            return result;
        }catch(SQLException ex){
            ex.printStackTrace();
            return 0;
        }
    }
}
