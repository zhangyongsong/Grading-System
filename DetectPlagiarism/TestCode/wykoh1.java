import java.util.Scanner;

public class PrintHorizontalHistogram {

	public static void main(String[] args) {
		int numberStudents;
		
		Scanner in = new Scanner(System.in);
		System.out.print("");
		numberStudents = in.nextInt();
		
		int[] grades = new int[numberStudents];
		
		for (int i = 0; i < numberStudents; i++) {
			grades[i] = in.nextInt();
		}
		
		int[] bins = new int[10];		
		for (int i = 0; i < bins.length; i++) {
			bins[i] = 0;
		}
		
		for (int gradeNum = 0; gradeNum < grades.length; gradeNum++) {
			int binNum;
			if (grades[gradeNum] < 100) {
				binNum = grades[gradeNum]/10;
			} else {
				binNum = 9;
			}
			bins[binNum]++;
			
		}

		for (int i = 0; i < bins.length; i++) {
			int lowerbound = i*10;
			int upperbound = i*10 + 9;
			if (i == bins.length - 1) {
				upperbound = 100;
			}
			
			System.out.printf("%3d -%3d: ", lowerbound, upperbound);
			
			int numStars = bins[i];
			for (int count = 0; count < numStars; count++) {
				System.out.print("*");
			}
			System.out.println();
		}

		
	}
	
}
