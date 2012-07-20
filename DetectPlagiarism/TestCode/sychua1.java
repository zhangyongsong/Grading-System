import java.util.Scanner;
public class PrintHorizonalHistogram {
	public static void main(String[] args) {
		int numGrades; 
			
		Scanner in = new Scanner(System.in);
		numGrades = in.nextInt(); // get the number
		
		int[] grades = new int[numGrades];
		
		//get the grades 
		for (int i = 0;i<grades.length;i++){
			grades[i] = in.nextInt();
		}
				
		int[] histogramBin = new int[10];
		for (int i=0;i<10;i++){
			histogramBin[i] = 0;
		}//initialize
		
		int temp; //store the grades into the bin
		for (int i=0;i<numGrades;i++){
			if ( grades[i] == 100 ){
				histogramBin[9]++;
			}
			else{
			temp = grades[i]/10;
			histogramBin[temp]++ ;
			}
		}
		
		//printing out the histogram
		for(int i=0;i<10;i++){
			if( i == 9 ){
				System.out.print(" " + i*10 + " -" + ((i*10) + 10) + ": " );
			}
			else{
				if (i == 0){
					System.out.print("  " + i*10 + " -  " + ((i*10) + 9) + ": " );
				}
				else
					System.out.print(" " + i*10 + " - " + ((i*10) + 9) + ": " );
			}

			while ( histogramBin[i] != 0 ){
				System.out.print("*");
				histogramBin[i]--;
			}
			System.out.println();
		}
		
	}
}
