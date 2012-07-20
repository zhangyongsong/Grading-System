import java.util.Scanner;

public class PrintHorziontalHistogram {
	public static void main(String[] args) {
		
		Scanner in = new Scanner(System.in);
		
		int numGrades = in.nextInt();
		int grades[] = new int[numGrades];
		
		for (int i = 0; i<numGrades; i++){
			grades[i] = in.nextInt();
		}
		
		System.out.print("  0 -  9: ");
		for (int j = 0; j < numGrades; j++){
			if(grades[j]>=0 && grades[j]<10){
				System.out.print("*");
			}
		}
		
		System.out.println("");
		
		System.out.print(" 10 - 19: ");
		for (int j = 0; j < numGrades; j++){
			if(grades[j]>=10 && grades[j]<20){
				System.out.print("*");
			}
		}
		System.out.println("");
		
		System.out.print(" 20 - 29: ");
		for (int j = 0; j < numGrades; j++){
			if(grades[j]>=20 && grades[j]<30){
				System.out.print("*");
			}
		}
		System.out.println("");
		
		System.out.print(" 30 - 39: ");
		for (int j = 0; j < numGrades; j++){
			if(grades[j]>=30 && grades[j]<40){
				System.out.print("*");
			}
		}
		System.out.println("");
		
		System.out.print(" 40 - 49: ");
		for (int j = 0; j < numGrades; j++){
			if(grades[j]>=40 && grades[j]<50){
				System.out.print("*");
			}
		}
		System.out.println("");
		
		System.out.print(" 50 - 59: ");
		for (int j = 0; j < numGrades; j++){
			if(grades[j]>=50 && grades[j]<60){
				System.out.print("*");
			}
		}
		System.out.println("");
		
		System.out.print(" 60 - 69: ");
		for (int j = 0; j < numGrades; j++){
			if(grades[j]>=60 && grades[j]<70){
				System.out.print("*");
			}
		}
		System.out.println("");
		
		System.out.print(" 70 - 79: ");
		for (int j = 0; j < numGrades; j++){
			if(grades[j]>=70 && grades[j]<80){
				System.out.print("*");
			}
		}
		System.out.println("");
		
		System.out.print(" 80 - 89: ");
		for (int j = 0; j < numGrades; j++){
			if(grades[j]>=80 && grades[j]<90){
				System.out.print("*");
			}
		}
		System.out.println("");
		
		System.out.print(" 90 -100: ");
		for (int j = 0; j < numGrades; j++){
			if(grades[j]>=90 && grades[j]<=100){
				System.out.print("*");
			}
		}

	}

}
