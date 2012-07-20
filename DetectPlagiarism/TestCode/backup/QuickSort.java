// This java program is intended for me to review about java programming 
// and algorithms

import java.util.Scanner;

public class QuickSort{
	public static final int MAX_SIZE=100;
	public static void main(String args[]){
		int array[]=new int[MAX_SIZE];
		int length;
		Scanner sc=new Scanner(System.in);
		
		System.out.println("Enter the length of the array(less than 100): ");
		length=sc.nextInt();
		sc.nextLine();//clear the input buffer before getting data
		System.out.println("Input the array: ");
		
		for (int i=0; i<length; i++){
			System.out.print("Next Element=> ");
			array[i]=sc.nextInt();
		}
		
		printArray(array, length);
		
		quickSort(array, 0, length-1);
		System.out.println("\n\nAfter quick sort of the array");
		printArray(array, length);
	}
	
	// method for print out an array
	public static void printArray(int array[], int length){
		System.out.println("\nThe array is   ");
		for(int i=0; i<length; i++)
			System.out.print(array[i]+"   ");
	}
	
	public static void swap(int array[], int i, int j){
		int temp;
		temp= array[i];
		array[i]=array[j];
		array[j]=temp;
		return;
	}
	
	public static void quickSort(int array[], int p, int q){
		if (p==q)
			return;
		
		int h;
		h=p;	
		for(int i=h; i<=q; i++){
			if (array[h]>array[i]){
				swap(array, h, i);
				h++;
			}
			quickSort(array, p, h-1);
			quickSort(array, h+1, q);
		}
	}
}