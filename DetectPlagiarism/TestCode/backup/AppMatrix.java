// An application program for Matrix class

import java.util.Scanner;

public class AppMatrix{
	
	static final int RM1=2;
	static final int RC=3;
	static final int CM2=4;
	
	public static void main(String[] Args){
		Matrix m1, m2, m3;
		m1=new Matrix(RM1, RC);
		m2=new Matrix(RC, CM2);
		m3=new Matrix(RM1, CM2);
		
		Scanner sc=new Scanner(System.in);
		
		int value;
		System.out.println("KEY in the Matrix:");
		for(int i=1; i<=RM1; i++)
			for(int j=1; j<=RC; j++){
				System.out.print("m1("+ i+", "+j+") is : ");
				value=sc.nextInt();
				m1.setElement(i, j, value);
			}
		
		m1.printMatrix();
		System.out.println();
		
		for(int i=1; i<=RC; i++)
			for(int j=1; j<=CM2; j++){
				System.out.print("m2("+ i+", "+j+") is : ");
				value=sc.nextInt();
				m2.setElement(i, j, value);
			}

		m2.printMatrix();
		System.out.println();
		m3=m3.multiply(m1, m2);
		m3.printMatrix();
	}
}