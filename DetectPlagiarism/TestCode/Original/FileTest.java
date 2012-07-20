/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
import java.io.*;
import java.util.ArrayList;
import java.util.Scanner;
/**
 *
 * @author ZHANG YONGSONG
 */
public class FileTest {
    private static String parentDir = "../";
    private static String newLine = System.getProperty("line.separator");
    public static void main(String[] args) throws IOException{
        File2String fxy = new File2String("E:/PROGRAMMING/Java_Programs/Exercises/RomanNumeral.java");
        fxy.readFile();
        fxy.process();
		inputReader();
        PrintWriter pw = new PrintWriter(new BufferedWriter(new FileWriter
                ("E:/Program Files/WampServer/www/Remove Comment/abc.java")));
        pw.println(fxy.fileContent+ "\n\n"+ fxy.getOutput());
        pw.close();
        return;
    }
    
    public static ArrayList inputReader(){
		DbQuery db = new DbQuery();
		//String query;
		ResultSet rs = null;
		double totalPrice =0;
		int totalQty =0;
		Iterator it = cart.entrySet().iterator();
		while (it.hasNext()) {
			Map.Entry book = (Map.Entry)it.next();
			Integer bookId = (Integer)book.getKey();
			Integer qty = (Integer)book.getValue();
			rs = db.queryBook("bookId", bookId.toString());
		}
	}
}
