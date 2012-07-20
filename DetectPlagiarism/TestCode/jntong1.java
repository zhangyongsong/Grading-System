import java.util.Scanner;

public class PrintHorizontalHistogram {
	public static int[] grades;
	public static int[] num = new int [10];    // to put in 0 - 10 etc
	
	public static void main(String[] args) {
		readHistogram();
		calculateHistogram();
		printHistogram();
	}
	
	public static void readHistogram() {
		Scanner in = new Scanner(System.in);
		int numStudents = in.nextInt();
		grades = new int[numStudents];
		for(int i =0; i<numStudents; i++) {
			grades[i] = in.nextInt();
		}
	}
	
	public static void calculateHistogram() {
		for (int i =0; i<grades.length; i++) {
			if (grades[i] == 100)
				num[9]++;
			else
				num[grades[i]/10]++;
		}
	}
	
	public static void printHistogram() {
		for(int i=0; i<num.length; i++) {
			if(i==9)
				System.out.print(" " + i*10 + " -" + "100: " );
			else if (i==0)
				System.out.print("  " + i + " -  " + (num.length-1) + ": ");
			else
				System.out.print(" " + i*10 + " - " + (i*10 + 9) + ": ");
			for (int star =0; star <num[i]; star++) {
				System.out.print("*");
			}
			System.out.println();
		}
	}
}
