import java.util.Scanner;
public class PrintHorizontalHistogram {
	
	public static int[] bins = new int[10];
	public static int[] grades;
	public static void main(String[] args){
		
		Scanner in=new Scanner(System.in);
		//System.out.print("Enter the number of students: ");
		
		int numStudents=in.nextInt();
	   
		int[] grades= new int[numStudents];
	   for(int i=0;i<numStudents;i++){
		  // System.out.print("Enter the grade for student " + (i + 1) + ": ");
		   grades[i]= in.nextInt();
	   }
	   
		for(int i=0;i<bins.length;i++){
			bins[i]=0;
		}
		for(int gradeNum=0; gradeNum< grades.length;gradeNum++){
			int binNum;
			if(grades[gradeNum]<100){
				binNum=grades[gradeNum]/10;
			}else{
				binNum=9;
			}
			bins[binNum]++;
		}
		for(int i=0;i<bins.length;i++){
			int lowerbound=i*10;
			int upperbound=i*10+9;
			if(i==bins.length-1){
				upperbound=100;
			}
			System.out.printf("%3d -%3d: ", lowerbound,upperbound);
			int numStars=bins[i];
			for(int count=0;count<numStars;count++){
				System.out.print("*");
			}
			System.out.println();
		}
		System.out.println();
		}

	}

