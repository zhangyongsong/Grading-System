// This class is an inheritance of TwoDimensionalShape class 
// Date of Creation: 26 May 2009

package Practise;

/**
 * @author YongSong
 *
 */
public class Rectangle 
	extends TwoDimensionalShape{
	// private variable of the class Rectangle
	private double xCord, yCord;
 	private String color;
	private double length;
	private double width;
	
	// Constructors
	public Rectangle(){
		super();
		length=0;
		width=0;
	}
	
	public Rectangle(double x, double y, String color, double length, double width){
		super(x, y, color);
		this.length=length;
		this.width=width;
	}
	
	// Methods
	public double findArea(){
		return length*width;
	}
	
	public double findCircum(){
		return 2*(length+width);
	}
	
	public double findDiag(){
		return Math.sqrt(length*length+width*width);
	}
	
	public double findDiagXPosition(){
		return xCord+length;
	}
	
	public double findDiagYPosition(){
		return yCord+width;
	}
	
	// Accessors and Modifiers
	public double getLength(){ return length;}
	public double getWidth(){return width;}
	public void setLength(double l){
		length=l;
	}
	public void setWidth(double w){
		width=w;
	}
	
	public void print(){
		super.print();
		System.out.println("The length of the Rectangle is "+ length+".");
		System.out.println("The width of the Rectangle is "+width+".");	
	}
}