import java.util.Scanner;

public class HorizontalGradeHistogram {

	public static void main(String[] args) {
		int num;
		Scanner in = new Scanner(System.in);

		num = in.nextInt();
		int[] values = new int[num];

		for (int i = 0; i < num; i++) {
			values[i] = in.nextInt();
		}
		
		for (int i = 0; i<10; i++) {
			if (i==0)
				System.out.print("  0 -  9: ");
			else if (i==9)
				System.out.print(" " + i*10 + " -" + ((i+1)*10) + ": ");
			else
				System.out.print(" " + i*10 + " - " + ((i+1)*10-1) + ": ");
			
			for (int j=0; j<num; j++) {
				if ((values[j]/10)==i)
					System.out.print("*");
				
				if ((values[j]==100) && i==9)
					System.out.print("*");
			}
			
			System.out.println();
		}
	}
}