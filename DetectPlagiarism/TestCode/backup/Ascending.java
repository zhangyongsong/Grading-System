/*
 * This program read in n integeers and then output them in ascending order
 *
 * Author: Zhang Yongsong
 */

import java.util.Scanner;
public class Ascending{
	static final int SIZE=6;
	public static void main(String[] Args){
		int[] array=new int[SIZE];
		input(array);
		
		System.out.println("\nThe array is ");
		display(array);
		
		sort(array);
		
		System.out.println("\nThe new ascending array is ");
		display(array);
	}
	
	// This method reads in the numbers
	public static void input(int[] array){
		System.out.println("Enter the numbers one by one: ");
		
		Scanner sc=new Scanner(System.in);
		
		for(int i=0; i<array.length; i++)
		{
			System.out.print("Next integer=>");
			array[i]=sc.nextInt();
		}
	}
	
	// This method displays the array
	public static void display(int[] array){
		//System.out.println("The array is ");
		for(int i=0; i<array.length; i++)
		{
			System.out.print(array[i]+"   ");
		}
		
		System.out.println();
	}
	
	
	// This method is the core of the program, which try to arrange the integers
	public static void sort(int[] array){
		int ch=1;
		while(ch==1)
		{
			for(int i=0; i<array.length-1; i++)
			{
				if(array[i]>array[i+1])
				{
					
					int temp;
					temp=array[i];
					array[i]=array[i+1];
					array[i+1]=temp;
					
					ch=1;
					
					break;
				}
			
				ch=0;
			}
			
		}
	}
}