import java.util.Scanner;
public class PrintHorizontalHistogram {

		public static void main(String[] args) {
		int[] grades;
		int numSt,num;
		int[] bins=new int[10];
		
		Scanner in=new Scanner(System.in);
		numSt=in.nextInt();//read number of students
		grades=new int[numSt];
		for(int i=0;i<numSt;i++){
			grades[i]=in.nextInt();//read grades
		}
		//collect histogram bins
		for(int i=0;i<numSt;i++){
			num=grades[i] / 10;//check the range
			for(int j=0;j<10;j++){
				if (num==j) bins[j]++;//collect the bins
			}
			if(num==10) bins[9]++;//for 100
		}
		
		//print the histogram
		for(int line=0;line<10;line++){
			if(line==9){
				System.out.printf("%3d -%3d: ",line*10,(line*10+10));//print the last line
			}else{
			System.out.printf("%3d -%3d: ",line*10,(line*10+9));//print rest head lines
			}
			
			for(int bin=0;bin<bins[line];bin++){//print bins
				System.out.print("*");
		    }
			System.out.println();
		}
		

	}

}
