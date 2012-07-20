// Two dimensional Shape

package Practise;

public abstract class TwoDimensionalShape implements Shape{
	private double xCord, yCord;
	private String color;
	
	//Constructors
	public TwoDimensionalShape(){
		xCord=0.0;
		yCord=0.0;
		color=null;
	}
	public TwoDimensionalShape(double x, double y, String color){
		xCord=x;
		yCord=y;
		this.color=color;
	}
	
	//abstract methods
	public abstract double findArea();
	public abstract double findCircum();
	public abstract double findDiag();
	public abstract double findDiagXPosition();
	public abstract double findDiagYPosition();
	//Accessors
	public double getXCord(){
		return xCord;
	}
	public double getYCord(){
		return yCord;
	}
	public String getColor(){
		return color;
	}
	
	//Modifiers
	public void setXCord(double x){
		xCord=x;
	}
	public void setYCord(double y){
		yCord=y;
	}
	public void setColor(String color){
		this.color=color;
	}
	
	public void print(){
		System.out.println("The color is "+color);
		System.out.println("This Tow dimensional Shape is located at" +xCord+", "+yCord+".");
	}
}