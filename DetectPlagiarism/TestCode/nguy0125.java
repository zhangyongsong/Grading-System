import java.util.Scanner;

public class PrintHorizontalHistogram{	//Saved as PrintHorizontalHistogram.java

	
	static int numStudent, grades[];	//Store number of students and their grades accordingly
	static int[] bin = new int[10];		//Store the grades in bins of 0 to 9
	
	public static void main(String[] args) {
		
		readGrades();					//Read the input grades from keyboard
		computeHistogram();				//Throw the grades into corresponding bins
		drawHistogram();				//Print out the histogram

	}
	
	//Read the input grades from keyboard
	public static void readGrades(){

		Scanner in = new Scanner(System.in);	//Declare new Scanner called in

		numStudent = in.nextInt();		//Scan number of students

		grades = new int[numStudent];	//Declare size to the array grades (equals to number of students)

		//Use for loop to input the grades
		for (int student = 0; student < numStudent; student++) {
			
			grades[student] = in.nextInt();		//Read the grade for the corresponding student
			
			//Enter the next int to the current student if the grade is out of range
			while (grades[student] > 100 || grades[student] < 0) {
				grades[student] = in.nextInt();
			}
		}
	}
	
	//Throw the grades into corresponding bins
	public static void computeHistogram(){

		//set bin to count grades
		for (int i=0; i<10; i++){
			bin[i]=0;		//Initialize the bins
		}
		
		//Accumulate the grades into corresponding bins
		for (int student = 0; student< numStudent; student++){
			
			if (grades[student]==100){			//Count grade 100 into bin 9
				bin[9]++;
			} else {
				bin[(grades[student]/10)] += 1;	//Count the grade according to its value 
			}
		}
	}

	//Print out the histogram
	public static void drawHistogram(){

		for (int i = 0; i < 10; i++){			//A loop to print out histogram for each bin
			
			if (i == 9){
				System.out.printf(" 90 -100: ");
			} else{
				System.out.printf("%3d -%3d: ", i*10, i*10+9);	
			}
			
			
			for(int star = 0; star < bin[i]; star++){		//An inner loop to print out the star * in each bin
				System.out.printf("*");
			}
			
			
			System.out.println();
		}
	}
}