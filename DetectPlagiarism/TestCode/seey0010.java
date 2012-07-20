import java.util.Scanner;

public class PrintHorizontalHistogram {

	public static void main(String[] args) {
		
		Scanner in = new Scanner(System.in);
		
		int numGrades = in.nextInt();
		int inputs[] = new int[numGrades];
		
		for (int i = 0; i < numGrades; i++) {
			inputs[i] = in.nextInt();
		}
		
		int bins[] = new int [10];
		for (int i = 0; i< inputs.length; i++) {
			if (inputs[i] == 100) {
				bins[9]++;
			}
			
			else if(inputs[i] < 10) {
				bins[0]++;
			}
			
			else {
				int index = inputs[i]/10;
				bins[index]++;
			}
		}
		
		for (int i = 0; i < bins.length; i++) {
			if (i == 0) {
				System.out.print("  0 -  9: ");
				for (int star = 0; star < bins[i]; star++) {
					System.out.print("*");
				}
			}
			
			else if(i > 0 && i < 9){
				System.out.print(" " + i + "0 - " + i + "9: ");
				for (int star = 0; star < bins[i]; star++) {
					System.out.print("*");
				}
			}
			
			else {
				System.out.print(" " + i + "0 -100: ");
				for (int star = 0; star < bins[i]; star++) {
					System.out.print("*");
				}
			}
			
			System.out.println();
		}	

	}

}
