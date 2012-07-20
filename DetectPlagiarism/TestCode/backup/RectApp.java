/* @author: Zhang Yongsong
*  Date: 26th May 2009
*  An application program
*/

package Practise;
public class RectApp{
	public static void main(String[] Args){
		Rectangle rect1, rect2;
		rect1= new Rectangle();
		rect2=new Rectangle(10, 10, "black", 20, 12);
		System.out.println("The color is "+rect1.getColor());
		System.out.println("The difference between their areas is "+(rect2.findArea()-rect1.findArea()));
	}
}