//########################################################################################
//frmCheck.js
//Dynamic form validation Script
//It initializes array from the page with their respective element nos.
//
//First Digit :- value 0 and 1
//0 - Not a compulsory field
//1 - Required field
//
//Second Digit :- value starts from 0 - 9
//0 - Treats as a normal field. validation not required
//1 - Numeric
//2 - Email
//3 - Date format (dd/mm/yyyy)
//4 - Date format (mm/dd/yyyy)
//5 - password
//6 - Confirm Password [please set a message for comparing password]

//set error message for password
var conpwd = "Password and Confirm Password does not match."  ;

//7 - IP Address
//8 - phone

//Third Digit :- value 0 and 1
//0 - Length checking is not required
//1 - Length checking is required
//After 3rd digit it assums rest value as maxlength required for input.
//########################################################################################
//Code Starts here
// Followed by one or more whitespace characters


var whitespace = " \t\n\r";

// Followed by one or more characters, followed by EOI.
var reEmail = /^.+\@.+\..+$/ ;
//var reEmail = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i

// BOI, followed by one or more digits, followed by EOI.
var reInteger = /^\d+$/

var defaultEmptyOK = false

var dtCh= "/";
var minYear=1900;
var maxYear=2100;

// Check whether string s is empty.
 function isEmpty(s)
      {return ((s == null) || (s.length == 0))}

// Returns true if string s is empty or
// whitespace characters only.

function isWhitespace (s)
      {
           var i;

           // Is s empty?
           if (isEmpty(s)) return true;

           // Search through string's characters one by one
           // until we find a non-whitespace character.
           // When we do, return false; if we don't, return true.

           for (i = 0; i < s.length; i++)
           {
                // Check that current character isn't whitespace.
                var c = s.charAt(i);

                if (whitespace.indexOf(c) == -1) return false;
           }

           // All characters are whitespace.
           return true;
      }

function Trim(String)
{
str=String;
  while (str.charAt(str.length - 1)==" ")
    str = str.substring(0, str.length - 1);
  while (str.charAt(0)==" ")
    str = str.substring(1, str.length);
  return str;
}

// isEmail (STRING s [, BOOLEAN emptyOK])

function isEmail (s)

{

	if (isEmpty(s))
       if (isEmail.arguments.length == 1) return defaultEmptyOK;
       else return (isEmail.arguments[1] == true);

    else {
       return reEmail.test(s)
    }

}

// Declaring required variables
var digits = "0123456789";
// non-digit characters which are allowed in phone numbers
var phoneNumberDelimiters = "()- ";
// characters which are allowed in international phone numbers
// (a leading + is OK)
var validWorldPhoneChars = phoneNumberDelimiters + "+";
// Minimum no of digits in an international phone no.
var minDigitsInIPhoneNumber = 10;

function isInteger(s)
{   var i;
    for (i = 0; i < s.length; i++)
    {
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag)
{   var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++)
    {
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

//-------------------------------------------------------------------
// isNumeric(value)
//   Returns true if value contains a positive float value
//-------------------------------------------------------------------
function isNumeric(val){return(parseFloat(val,10)==(val*1));}

// Function to check Phone field

function isPhone(strPhone)
{
	s=stripCharsInBag(strPhone,validWorldPhoneChars);
	return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
}

// Leap year calculation
function daysInFebruary (year){
	// Check for leap year
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}

function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   }
   return this
}

//DD/MM/YYYY Format
function isDateDMY(dtStr){
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	var strDay=dtStr.substring(0,pos1)
	var strMonth=dtStr.substring(pos1+1,pos2)
	var strYear=dtStr.substring(pos2+1)
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth)
	day=parseInt(strDay)
	year=parseInt(strYr)
//	if (pos1==-1 || pos2==-1){
	//	alert("The date format should be : DD/MM/YYYY")
	//	return false
//	}
	if (strMonth.length<1 || month<1 || month>12){
//		alert("Kindly specify a valid month")
		return false
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
//		alert("Kindly specify a valid day")
		return false
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
//		alert("Kindly specify a valid 4 digit year between "+minYear+" and "+maxYear)
		return false
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
//		alert("Kindly specify a valid date")
		return false
	}
return true
}

//MM/DD/YYYY Date Format
function isDateMDY(dtStr){
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	var strMonth=dtStr.substring(0,pos1)
	var strDay=dtStr.substring(pos1+1,pos2)
	var strYear=dtStr.substring(pos2+1)
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth)
	day=parseInt(strDay)
	year=parseInt(strYr)

	if (strMonth.length<1 || month<1 || month>12){
//		alert("Kindly specify a valid month")
		return false
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
//		alert("Kindly specify a valid day")
		return false
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
//		alert("Kindly specify a valid 4 digit year between "+minYear+" and "+maxYear)
		return false
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
//		alert("Kindly specify a valid date")
		return false
	}
return true
}


function chkForm(f)
{
//Loop through each element.
	 for (i=0; i<(f.length); i++)
	 {
		 t=f[i];
		 arr=t.id;
		//msg = " Kindly specify "+ t.title +" to allow us to complete our records."
		 msg="The form is incomplete. Kindly specify the " + t.title + "."

		 	 var vFlag = true
			//Store Name of the form elements
			var tval = t.id ;
			
			//Pass element arrays from the form to validate rules

			if (isWhitespace(arr)){
				tval="0000000";
				
			} //If array is empty then store '00000000' to its value
			if (!(isWhitespace(arr))){tval = arr;} // if array is not empty then store to tval variable

			//seprate values from the array to follow the rules


			var fPos = tval.substring(0,1) ; // Required / Not
			var sPos = tval.substring(1,2) ; // Type e.g. Normal Text, phone, numeric, email , password, etc.
			var tPos = tval.substring(2,3) ; // check Length required / Not
			var lPos = tval.substring(3,8) ; // If length required how much it is
			var myVal = Trim(t.value)
			if (myVal=="" && tval!="0000000" && fPos=="1" && t.type!="select-one"){alert(msg);t.value="";t.focus();return false;}
			if (myVal!="" && tval!="0000000" && fPos=="0"){t.value=Trim(t.value);}
			if (myVal!="" && tval!="0000000"){t.value=Trim(t.value);}

		 //Initialize Input type for each form element

		 switch (t.type)
		 	{
	 	     case "select-one":
	 	     {
				switch (fPos)
				{
					case "1":
					{
			  			//If value is not selected from dropdown
			  			if(t.options[0].selected && t.value=='')
					  	{
					  	msg="The form is incomplete. Kindly select from the " + t.title ;
			  			alert (msg);
			  			t.focus();
			  			return false;
						}
					}
				break;
				}

			 break;
		     }
	 	     case "password":	//If Input type is Password
	 	     {
	 		 		switch (fPos)
			 		{
						case "1":
						{

							//If password field starts with whitespace
							if(sPos=="5" && (isWhitespace(t.value)))
							{
							alert(msg);
							t.focus();
							return false;
							}

							if(sPos=="6")
							{
							 p=f[i-1];
							 c=f[i];
							 if(isWhitespace(c.value))
							    {
								alert(msg);
								c.focus();
								return false;
							     }
								if(p.value!="" && c.value!="")
								 {
								  if (p.value!=c.value)
								  {
								  alert(conpwd);
								  c.focus();
								  return false;
								  }
								 }
							 }

						break ;
						}
					break;
					}
			 break;
	 	     }
			 case "hidden":
			 {
				////check for max length
				
				if(sPos=="0"  && tPos=="1" && (!isEmpty(t.value)))
				{
					if(t.value.length > parseFloat(lPos))
					  {
					  var newmsg = "You have exceeded the max limit for this field. Kindly specify the " + t.title + " & it should not be more than " + parseFloat(lPos) + " characters "
					  
					  alert(newmsg);
					 
					  return false;
					  }					
				}
				break;
			 }
		     case "text": // If Input type is Text
		     {
			 		switch (fPos)
			 		{
						case "0": // if field is not compulsory
						{
							////check for max length
							
						if(parseFloat(lPos)>0)
						{
							
							if(t.value.length > parseFloat(lPos))
							  {

							  var newmsg = "You have exceeded the max limit for this field. Kindly specify in the " + t.title + " & it should not be more than " + parseFloat(lPos) + " characters "
							  
							  alert(newmsg);
							
								 t.focus();
							  return false;
							  }
						}
							// If value is not numeric
							if(sPos=="1" && (!(isWhitespace(t.value))) && (!(isNumeric(t.value))))
							{
							//alert(msg);
							alert("Kindly specify a valid number");
							t.focus();
							return false;
							}

							// If entered value is not an Email
							if(sPos=="2" && (!(isWhitespace(t.value))) && (!(isEmail(t.value))))
							{
							alert("Kindly specify a valid " + t.title );
							t.focus();
							return false;
							}

							// If Date is not empty then check format i.e. dd/mm/yyyy
							if(sPos=="3" && (!(isWhitespace(t.value))) &&  (!(isDateDMY(t.value))))
							{
							alert(msg);
							t.focus();
							return false;
							}

							// If Date is not empty then check format i.e. mm/dd/yyyy
							if(sPos=="4" && (!(isWhitespace(t.value))) && (!(isDateMDY(t.value))))
							{
							alert(msg);
							t.focus();
							return false;
							}

							//If phone is not empty then check valid characters
							if(sPos=="8" && (!(isWhitespace(t.value))) &&  (!(isPhone(t.value))))
							{
							alert(msg);
							t.focus();
							return false;
							}

						break;
						} // End of Case for fields which are not compulsory

						case "1": // If it is compulsory field
						{
							
						////check for max length
						if(sPos=="1"  && tPos=="1" && (!isEmpty(t.value)))
						{
							if(t.value.length > parseFloat(lPos))
							  {
							  var newmsg = "You have exceeded the max limit for this field. Kindly specify the " + t.title + " & it should not be more than " + parseFloat(lPos) + " characters ";
							  //var newmsg = "Maximum '" + parseFloat(lPos) + "' characters can be entered"
							  alert(newmsg);
							  t.focus();
							  return false;
							  }
							}
							//Check white space or empty input
							if(sPos=="0" && (isWhitespace(t.value)))
							{
							alert(msg);
							t.focus();
							return false;
							}
							//Check if all values are numeric
							if(sPos=="1" && (!(isNumeric(t.value))))
							{
							alert("Kindly specify a valid number");
							t.focus();
							return false;
							}
							//check for Valid Email
							if(sPos=="2" && (!(isEmail(t.value))))
							{
							alert("Kindly specify a valid " + t.title );
							t.focus();
							return false;
							}
							if((sPos=="3" || sPos=="4")&& isWhitespace(t.value))
							{
							alert(msg);
							t.focus();
							return false;
							}
							//Check Date format in dd/mm/yyyy
							if(sPos=="3" && (!(isDateDMY(t.value))))
							{
							alert("Kindly specify valid Date(dd/mm/yyyy)");
							t.focus();
							return false;
							}
							//Check date format in mm/dd/yyyy
							if(sPos=="4" && (!(isDateMDY(t.value))))
							{
							alert("Kindly specify valid Date(mm/dd/yyyy)");
							t.focus();
							return false;
							}
							//If password(with no pwd features) field starts with whitespace
							if(sPos=="5" && (isWhitespace(t.value)))
							{
							alert(msg);
							t.focus();
							return false;
							}
							//confirm password
							if(sPos=="6")
							{
							 p=f[i-1];
							 c=f[i];
							 if(isWhitespace(c.value))
							    {
								alert(msg);
								c.focus();
								return false;
							     }
								if(p.value!="" && c.value!="")
								 {
								  if (p.value!=c.value)
								  {
								  alert(conpwd);
								  c.focus();
								  return false;
								  }
								 }
							 }

							//Check for valid phone value
							if(sPos=="8" && (!(isPhone(t.value))))
							{
							alert("Invalid Phone number");
							t.focus();
							return false;
							}


							//Check for Empty IP
							if(sPos=="7" && (isEmpty(t.value)))
							{
								alert(msg);
								t.focus();
								return false;
							}

							//IP address validation
							else if(sPos=="7" && (!(isWhitespace(t.value))))
							{
								var countno;
								countno=t.value.split(".");

								//check if  splitted IP <> 4
								if(countno.length>4 || countno.length<4)
								{
								alert("Please provide a valid ip address eg:(192.168.1.202)");
								t.focus();
								return false;
								}
								for(var n=0;n<countno.length;n++)
								{
									//If its doesnt consist a numeric value
									if(isNaN(countno[n])==true)
									{
										alert("You can only enter numbers");
										t.focus();
										return false;
									}
									//check if each splitted numeric value is <=255
									if(countno[n]< 0 || countno[n]>255)
									{
										alert("Number must be less then 255 and greater then 0"+countno[n]);
										t.focus();
										return false;
									}

								}
							}
						break ;
					
						}
					break;
			 		}
			 	break ;
			}//End of input type text case

			case "textarea":
			{
		 		switch(fPos)
		 		{
		 			//Not required textarea
		 			case "0":
					{
						//Check only maxlength
						if(sPos=="0"  && tPos=="1" && (!isEmpty(t.value)))
						{
						  if(t.value.length > parseFloat(lPos))
						  {
						  var newmsg = "You have exceeded the max limit for this field. Kindly specify in the " + t.title + " & it should not be more than " + parseFloat(lPos) + " characters ";
						  //var newmsg = "Maximum '" + parseFloat(lPos) + "' characters can be entered"
						  alert(newmsg);

						  t.focus();
						  return false;
						  }
						}
					break;
					}
		 			//Required textarea
		 			case "1":
					{
						if(sPos=="0" && tPos=="1" && isWhitespace(t.value))
						  {
						  alert(msg);
						  t.value="";
						  t.focus();
						  return false;
						  }

						  //If Enter value length is great than maxlength
						  if(t.value.length > parseFloat(lPos))
						  {
						  var newmsg = "You have exceeded the max limit for this field. \n\nKindly specify in the " + t.title + " & it should not be more than " + parseFloat(lPos) + " characters ";
//						  var newmsg = "Maximum '" + parseFloat(lPos) + "' characters can be entered"
						  alert(newmsg);
						  t.focus();
						  return false;
						  }
			break;
			}
			break;
			}//End of required case for textarea
			break ;
			}//End of input type textarea
			break ;
		    }//End of switch for element Type
			if (vFlag == false){t.focus();return false;}
	 }//End of form Elements for loop

}//main function loop Ended here