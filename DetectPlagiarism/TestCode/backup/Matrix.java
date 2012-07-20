// This is a class for Matrix
public class Matrix{
	private int row;
	private int col;
	private int[] elements;
	
	// Constructor
	public Matrix(int r, int c){
		row=r;
		col=c;
		elements=new int[r*c];
	}
	
	//get element from this Matrix object
	public int getElement(int i, int j){
		if( i<=row && i>0 && j<=col && j>0)
			return elements[col*(i-1)+(j-1)];
		else
			return -1;
	}
	
	// set element in the Matrix object
	public void setElement(int i, int j, int value){
		if(i<=row && i>0 && j<=col && j>0)
			elements[col*(i-1)+(j-1)]=value;
	}
	
	// print Matrix
	public void printMatrix(){
		for(int i=0; i<row*col; i++){
			System.out.print(elements[i]+"  ");
			if ((i+1)%col==0)
				System.out.println();
		}
	}
	
	// Multiplication of two Matrices
	public Matrix multiply(Matrix lm, Matrix rm){
	/*	int rowLm=0, colLm=0, rowRm=0, colRm=0;
		
		for(int i=1; lm.getElement(i, 1)!=-1; i++)
			++rowLm;
		for(int j=1; lm.getElement(1, j)!=-1; j++)
			++colLm;
		for(int i=1; rm.getElement(i, 1)!=-1; i++)
			++rowRm;
		for(int j=1; lm.getElement(1, j)!=-1; j++)
			++colRm;
		
*/
		int rowLm=row, colRm=col;
		int colLm=0, rowRm=0;
	
		for(int j=1; lm.getElement(1, j)!=-1; j++)
			++colLm;
		for(int i=1; rm.getElement(i, 1)!=-1; i++)
			++rowRm;
		
		Matrix lr=new Matrix(rowLm, colRm);
		if (colLm!=rowRm)
			return lr;
		for(int i=1; i<=rowLm; i++){
			for(int j=1; j<=colRm; j++){
				int value=0; 
				if(colLm==rowRm){
					for(int k=1; k<=colLm; k++){
						value+=lm.getElement(i, k)*rm.getElement(k,j);
					}
				}
				else System.out.println("Error!");
				lr.setElement(i,j,value);
			}
		}
		return lr;
	}
}