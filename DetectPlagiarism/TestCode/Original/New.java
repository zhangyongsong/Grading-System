import java.util.HashMap;
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
	protected int romanToNumber(String roman){
		if (roman.equals(""))
			return 0;
			
			HashMap<Integer, Integer> cart = (HashMap)session.getAttribute("cart");
	if(cart == null || cart.size() ==0)
		out.println("Please select your book before proceed for order. Thanks!<br>");
	else{
		else if (roman.charAt(0)=='-')
			return -1 * romanToNumber(roman.substring(1));
		else if(roman.length() ==1)
			return symbolToValue.get(roman.charAt(0));
