/* This program rearrange the integers in one file and write them 
* in another file.
*/

import java.io.*;
public class Descending{
	static final int SIZE=100;
	public static void main(String[] Args){
		int[] array= new int[SIZE];
		try{
			BufferedReader brStream=new BufferedReader(new FileReader("input.txt"));
			PrintWriter pwStream=new PrintWriter(new BufferedWriter(new FileWriter("output.txt")));
			
			int n=0;
			String inputLine;
			
			// read in from file
			while((inputLine=brStream.readLine())!=null)
			{
				array[n]=Integer.parseInt(inputLine);
				n++;
			}
			
			
			// sort the number
			int check=1;
			while(check==1)
			{
				for(int i=0; i<n-1; i++)
				{
					if(array[i]<array[i+1])
					{
						int temp=array[i];
						array[i]=array[i+1];
						array[i+1]=temp;
						check=1;
						break;
					}
					
					check=0;
				}
			}
			
			// write the result into the file
			for(int i=0; i<n; i++)
			{
				pwStream.print(array[i]+"\t");
			}
			pwStream.println();
			
			
			brStream.close();
			pwStream.close();
		}
		
		catch(FileNotFoundException e){
			System.out.println(e.getMessage());
			e.printStackTrace();
		}
		
		catch(IOException e){
			System.out.println(e.getMessage());
			e.printStackTrace();
		}
		
		catch(ArrayIndexOutOfBoundsException e){
			
			System.out.println(e.getMessage());
			
			e.printStackTrace();
			
			System.out.println("Error!");
			
			System.exit(0);
		}
		
		finally{
			System.out.println("Programme Terminating...");
		}
	}
}