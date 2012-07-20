/* File Name: Encryption.java
* Description: This programme encrypted normal English 
* 	into some codes
* Author: Zhang Yongsong
* Date: 2 June, 2009
* 
*/

//package Trial;

import java.util.Scanner;
import java.io.*;

public class Encryption{
	public static void main(String[] Args){
/*		System.out.println("Enter the input file name:");
		
		Scanner sc= new Scanner(System.in);
		String inFileName=sc.next();
		
		System.out.println("Enter the output File Name:");
		
		String outFileName=sc.next();
*/		
		try{
//			File fl= new File(inFileName);
			File fl= new File("E:/PROGRAMMING/Java_Programs/JavaPg/src/Trial/in.txt");
			Scanner fsc= new Scanner(fl);
			
			PrintWriter pw=new PrintWriter(new BufferedWriter(new FileWriter("E:/PROGRAMMING/Java_Programs/JavaPg/src/Trial/out.txt")));
			String strAll="";
			while(fsc.hasNext()){
				String str=fsc.nextLine();
				String str1;
				str1=encrypt(str);
				strAll+=str1;
			}
			
//			System.out.println(strAll);
			pw.write(strAll);
			pw.flush();
			pw.close();
		}
		
		catch(FileNotFoundException e){
			System.out.println("File not Found!");
			e.printStackTrace();
			e.getMessage();
		}
		catch(Exception e){
			e.getMessage();
		}
		
		
		finally{
			System.out.println("Programme Terminating...");
		}
	}
	

	public static String encrypt(String str){
		String str1="";
		char ch, cha;
		int counter=0;

		while(counter<str.length()){
			ch=str.charAt(counter);
			if((int)ch>=65 && (int)ch<91)
				cha=(char)(91-(int)ch+64);
			else if((int)ch>=97 && (int)ch<123 )
				cha=(char)(123-(int)ch+96);
			else cha=ch;
			
			str1+=cha;
			counter++;
		}
		
		return str1;
	}
}