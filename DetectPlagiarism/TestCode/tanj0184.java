import java.util.Scanner;

public class Horizontalhisto {
	
	public static void main(String[] args) {
	
		Scanner in = new Scanner(System.in);
		
		
	int numGrades = in.nextInt();
	int[] Grades = new int[numGrades];
	
	for(int i =0; i< numGrades; i++)
	{ Grades[i] = in.nextInt();
	}
	
	System.out.print("  0 -  9: ");
	for(int i = 0; i<numGrades; i++)
	{ if(Grades[i] >= 0 && Grades[i] <= 9)
		System.out.print("*");
	}
	System.out.println("");
	
	System.out.print(" 10 - 19: ");
	for(int i = 0; i<numGrades; i++)
	{ if(Grades[i] >= 10 && Grades[i] <= 19)
		System.out.print("*");
	}
	System.out.println("");
	
	System.out.print(" 20 - 29: ");
	for(int i = 0; i<numGrades; i++)
	{ if(Grades[i] >= 20 && Grades[i] <= 29)
		System.out.print("*");
	}
	System.out.println("");
	
	System.out.print(" 30 - 39: ");
	for(int i = 0; i<numGrades; i++)
	{ if(Grades[i] >= 30 && Grades[i] <= 39)
		System.out.print("*");
	}
	System.out.println("");
	
	System.out.print(" 40 - 49: ");
	for(int i = 0; i<numGrades; i++)
	{ if(Grades[i] >= 40 && Grades[i] <= 49)
		System.out.print("*");
	}
	System.out.println("");
	
	System.out.print(" 50 - 59: ");
	for(int i = 0; i<numGrades; i++)
	{ if(Grades[i] >= 50 && Grades[i] <= 59)
		System.out.print("*");
	}
	System.out.println("");
	
	System.out.print(" 60 - 69: ");
	for(int i = 0; i<numGrades; i++)
	{ if(Grades[i] >= 60 && Grades[i] <= 69)
		System.out.print("*");
	}
	System.out.println("");
	
	System.out.print(" 70 - 79: ");
	for(int i = 0; i<numGrades; i++)
	{ if(Grades[i] >= 70 && Grades[i] <= 79)
		System.out.print("*");
	}
	System.out.println("");
	
	System.out.print(" 80 - 89: ");
	for(int i = 0; i<numGrades; i++)
	{ if(Grades[i] >= 80 && Grades[i] <= 89)
		System.out.print("*");
	}
	System.out.println("");
	
	System.out.print(" 90 -100: ");
	for(int i = 0; i<numGrades; i++)
	{ if(Grades[i] >= 90 && Grades[i] <= 100)
		System.out.print("*");
	}
	System.out.println("");
	
	}
	

}
