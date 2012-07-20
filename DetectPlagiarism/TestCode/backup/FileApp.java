/* Java program for testing basic File Class
** Developed by Yongsong
** On Mar 24th
*********************************************/

import java.io.File;

public class FileApp{
	public static void main(String[] Args) throws Exception {
		File myFile = new File("E:\\PROGRAMMING\\JavaTest.java");
		System.out.println("Java File class testing...");
		try{
		if (!myFile.exists()){
			myFile.createNewFile();
			System.out.println("File Created: "+ myFile.toString());
		}
		else
			System.out.println("Filename is: "+ myFile.toString());
		
		File parentFolder = new File(myFile.getParent());
		File subFile = new File(parentFolder, "Testing.ABC");
		subFile.createNewFile();
		System.out.println("File Test.ABC created!");
		System.out.println("Programming Ending...");
		} catch(Exception ex){
		System.err.println(ex.getMessage());
	}
	}  
}