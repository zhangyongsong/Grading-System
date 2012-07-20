/*
 * File Name: FourStrings.java
 * This programme wants the user to enter four Strings
 * The output is the arrangement of this four Strings
 * Author: Zhang Yongsong
 * Date: 31 March, 2009
 */

import java.util.Scanner;
public class FourStrings{
	public static void main(String[] Args){
		String[] strArr= new String[4];
		Scanner sc= new Scanner(System.in);
		
		System.out.println("Enter the four words first=>");
		
		// read in the String
		int i;
		for(i=0; i<4; i++)
			strArr[i]=sc.next();
		
		char j;
		for(j='a'; j<='z'; j++)
		{
			for(int k=0; k<4; k++)
			{
				if (j==strArr[k].charAt(0))
					System.out.print(strArr[k]+'\t');
			}
		}
		
		System.out.println();
		
		for(j='z'; j>='a'; j--)
		{
			for(int k=0; k<4; k++)
			{
				if (j==strArr[k].charAt(0))
					System.out.print(strArr[k]+'\t');
			}
		}
	}
}
