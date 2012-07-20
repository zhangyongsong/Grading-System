/* This file is the time when I was trying to 
 * use pseudocode to help me improve my program
 * Author: Zhang Yongsong
 * Date: 05/05/2009
 *
 * the program can only deal with numbers quantity not greater than certain amount 
 */
 
 import java.util.Scanner;
 import java.io.*;
 
 public class Reallocation{
 	static final int SIZE=100;
 	public static void main(String[] Args){
 		
 		try{
	 		Scanner fr=new Scanner(new File("inFile.txt"));
	 		PrintWriter pw=new PrintWriter(new BufferedWriter(new FileWriter("outFile.txt")));
	 		int n=0;
	 		int[] array=new int[SIZE];
	 		while(fr.hasNext()){
	 			array[n]=fr.nextInt();
	 			n++;
	 		}
	 		
	 		arrange(array, n);
	 		
	 		for(int i=0; i<n; i++){
	 			pw.print(array[i]);
	 			pw.print("\t");
	 			if(i%5==4)
	 				pw.println();
	 		}
	 		
	 		pw.close();
 		}
 		
 		catch(FileNotFoundException e){
 			System.out.println(e.getMessage());
 		}
 		
 		catch(IOException e){
 			System.out.println(e.getMessage());
 			e.printStackTrace();
 		}
 		
 		finally{
 			System.out.println("Program terminating...");
 		}
 		
 	}
 	
 	public static void arrange(int[] array, int n){
 		
 		for(int i=0; i<n; i++){
 			
 			int j;
 			for(j=i; j<n; j++){
 				if(array[i]>array[j]){
 					int temp=array[i];
 					array[i]=array[j];
 					array[j]=temp;
 				}
 			}
 		}
 		
 	}
 }