import java.util.Scanner;

public class PrintHorizontalHistogram {

	public static int[] grades;
		
	public static void main(String[] args) {
		
		Scanner in = new Scanner(System.in);
		
		//read line 1: number of students
		int numStudents = in.nextInt();
		
		//read line 2: the grades
		int[] grades =  new int[numStudents];
		for (int i = 0; i < grades.length; i++){
			grades[i] = in.nextInt();
		}
		
		int[] bins = new int[10];
		for(int i = 0; i < numStudents; i++){
			if(grades[i] == 100){
				bins[9]++;
			}else{
				bins[grades[i]/10]++;
			}
		}

	

		for (int bin = 0; bin < bins.length; bin++) {
			if(bin==9){
				System.out.print(" 90 -100: ");
			}else{
				System.out.printf("%3d -%3d: ", bin*10, bin*10+9);
			}
			for(int star = 0; star < bins[bin]; star++){
				System.out.print("*");
			}
			System.out.println();
			
		}
		
	}
}
