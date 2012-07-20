// This is a toy program for calculating
// Sum and Average of number from 1 to 100

public class SumAndAverage{
	public static void main(String[] args){
		int sum=0;
		double average =0.0;
		for (int i=1; i<=100; i++){
			sum+=i;
		}
		average = sum/100.0;
		System.out.printf("The Sum is %d.\nThe Average is %.1f.\n", sum, average);
		return;
	}
}