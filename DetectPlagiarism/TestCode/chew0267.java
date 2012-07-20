import java.util.Scanner;

public class HorizontalHistogram {

	public static void main(String[] args) {
		
		Scanner in = new Scanner(System.in);
		
		int numGrades = in.nextInt();
		
		int[] grades = new int[numGrades];
		for(int i = 0; i < numGrades; i++) {
			grades[i] = in.nextInt();
		}
		
		int[] storage = new int[10];
		for(int i = 0; i < 10; i++) {
			storage[i] = 0;
		}
		
		for(int gradeIndex = 0; gradeIndex < numGrades; gradeIndex++) {
			int storageIndex;
			if (grades[gradeIndex] < 100) {
				storageIndex = grades[gradeIndex]/10;
			} else {
				storageIndex = 9;
			}	
			storage[storageIndex]++;
		}
		
		for(int i = 0; i < storage.length; i++) {
			int start = i*10;
			int end = i*10+9;
			if (i == storage.length - 1) {
				end = 100;
			}
			
			System.out.printf("%3d -%3d: ", start, end);
			
			int numStars = storage[i];
			for(int count = 0; count < numStars; count++) {
				System.out.print("*");
			}
			System.out.println();
		}

	}

}
