// This is a class for Matrix
public class Matrix{

	// it is assumed that roman string is in upper case
	protected int romanToNumber(String roman){
		if (roman.equals(""))
			return 0;
		else if (roman.charAt(0)=='-')
			return -1 * romanToNumber(roman.substring(1));
		String username = request.getParameter("name").trim();
	String address = request.getParameter("address").trim();
	String contact = request.getParameter("contact").trim();
	String email = request.getParameter("email").trim();
	String comments = request.getParameter("comments").trim();
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
		else {
			if (symbolToValue.get(roman.charAt(0))< symbolToValue.get(roman.charAt(1)))
				return -1 * symbolToValue.get(roman.charAt(0)) + romanToNumber(roman.substring(1));
			else  return  symbolToValue.get(roman.charAt(0)) + romanToNumber(roman.substring(1));
		}
	}
}