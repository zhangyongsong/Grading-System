import java.util.Scanner;

public class PrintHorizontalHistogram {
	public static void main(String[] args) {
		
		int n;
		Scanner input = new Scanner(System.in);
		n = input.nextInt();
		int[] grades = new int[n];
		
		for(int i = 0; i < n; i++){
			grades[i] = input.nextInt();
		}
		
		System.out.printf("  0 -  9: ");
		for(int i = 0; i < n; i++){
			if(grades[i] >= 0 && grades[i] <= 9){
				System.out.print("*");			
			}
		}
		System.out.println();
		
		System.out.printf(" 10 - 19: ");
		for(int i = 0; i < n; i++){
			if(grades[i] >= 10 && grades[i] <= 19){
				System.out.print("*");			
			}
		}
		System.out.println();
		
		System.out.printf(" 20 - 29: ");
		for(int i = 0; i < n; i++){
			if(grades[i] >= 20 && grades[i] <= 29){
				System.out.print("*");			
			}
		}
		System.out.println();
		
		System.out.printf(" 30 - 39: ");
		for(int i = 0; i < n; i++){
			if(grades[i] >= 30 && grades[i] <= 39){
				System.out.print("*");			
			}
		}
		System.out.println();
		
		System.out.printf(" 40 - 49: ");
		for(int i = 0; i < n; i++){
			if(grades[i] >= 40 && grades[i] <= 49){
				System.out.print("*");			
			}
		}
		System.out.println();
		
		System.out.printf(" 50 - 59: ");
		for(int i = 0; i < n; i++){
			if(grades[i] >= 50 && grades[i] <= 59){
				System.out.print("*");			
			}
		}
		System.out.println();
		
		System.out.printf(" 60 - 69: ");
		for(int i = 0; i < n; i++){
			if(grades[i] >= 60 && grades[i] <= 69){
				System.out.print("*");			
			}
		}
		System.out.println();
		
		System.out.printf(" 70 - 79: ");
		for(int i = 0; i < n; i++){
			if(grades[i] >= 70 && grades[i] <= 79){
				System.out.print("*");			
			}
		}
		System.out.println();
		
		System.out.printf(" 80 - 89: ");
		for(int i = 0; i < n; i++){
			if(grades[i] >= 80 && grades[i] <= 89){
				System.out.print("*");			
			}
		}
		System.out.println();
		
		System.out.printf(" 90 -100: ");
		for(int i = 0; i < n; i++){
			if(grades[i] >= 90 && grades[i] <= 100){
				System.out.print("*");			
			}
		}
		

	}

}
