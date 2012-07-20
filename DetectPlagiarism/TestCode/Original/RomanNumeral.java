import java.util.HashMap;

/**
* Roman Numerals
* This class create Roman numbers object
* and do the corresponding calculations
* Maximum should be less than 1 million
*/
public class RomanNumeral{
DbQuery db = new DbQuery();
			//String query;
			ResultSet rs = null;
			double totalPrice =0;
			int totalQty =0;
			Iterator it = cart.entrySet().iterator();
			while (it.hasNext()) {
				Map.Entry book = (Map.Entry)it.next();
				Integer bookId = (Integer)book.getKey();
				Integer qty = (Integer)book.getValue();
				rs = db.queryBook("bookId", bookId.toString());
				if(rs.next()){

	// it is assumed that roman string is in upper case
	protected int romanToNumber(String romans){
		if (romanss.equals(""))
			return 0;
			
			HashMap<Integer, Integer> cart = (HashMap)session.getAttribute("cart");
	if(cart == null || cart.size() ==0)
		out.println("Please select your book before proceed for order. Thanks!<br>");
	else{
		else if (romanss.charAt(0)=='-')
			return -1 * romanssToNumber(romanss.substring(1));
		else if(romanss.length() ==1)
			return symbolToValue.get(romanss.charAt(0));

		else {
			if (symbolToValue.get(romanss.charAt(0))< symbolToValue.get(romanss.charAt(1)))
				return -1 * symbolToValue.get(romanss.charAt(0)) + romanssToNumber(romanss.substring(1));
			else  return  symbolToValue.get(romanss.charAt(0)) + romanssToNumber(romanss.substring(1));
		}
	}
}
