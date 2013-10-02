scnShortcodeMeta={
	attributes:[
			{
				label:"Title",
				id:"title",
				help:"Title (optional)"
			},
			{
				label:"Image Size (in pixels):",
				id:"thumb_size",
				help:"Enter the name of the category you'd like to show posts from. Leave empty if you'd like to show posts from all categories."
			},
			{
				label:"Order direction:",
				id:"order",
				controlType:"select-control", 
				selectValues:['none', 'ID', 'title', 'date', 'menu_order','rand'],
				defaultValue: 'menu_order', 
				defaultText: 'menu_order',
				help:"How to order the items."
			},
			{
				label:"Order By:",
				id:"orderby",
				controlType:"select-control", 
				selectValues:['DESC', 'ASC'],
				defaultValue: 'DESC', 
				defaultText: 'DESC',
				help:"The order direction."
			},
			{
				label:"Show post title",
				id:"post_title",
				controlType:"select-control", 
				selectValues:['yes', 'no'],
				defaultValue: 'yes', 
				defaultText: 'yes',
				help:"Enable or Disable Title."
			},	
			{
				label:"Show excerpt",
				id:"excerpt",
				controlType:"select-control", 
				selectValues:['yes', 'no'],
				defaultValue: 'yes', 
				defaultText: 'yes',
				help:"Enable or disable excerpt."
			},
						{
				label:"Excerpt lenght",
				id:"excerpt_lenght",
				help:"Excerpt length (characters)."
			}
	],
	defaultContent:"",
	disablePreview: true,
	shortcode:"subpages"
};