=== Category and Taxonomy Image ===
2	Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=amu02.aftab@gmail.com&item_name=Donation+category+and+Custom+taxonomy+image
3	Tags: Category Image, Category Images, Categories Images, taxonomy image, taxonomy images, taxonomies images, category icon, categories icons, category logo, categories logos, admin, wp-admin, category image plugin, categories images plugin, category featured image, categories featured images, feature image for category, term image, tag image term images, tag images
4	Contributors: amu02aftab
5	Author: amu02aftab
6	Tested up to: 6.0.1
7	License: GPLv2
8	Requires at least: 3.5.0
9	Stable tag: 1.0
10
11	== Description ==
12	* Category and Taxonomy Image Plugin allow you to add image with category/taxonomy.
13	* you can use the following function into your templates to get category/term image:
14	<pre>
15	if (function_exists('get_wp_term_image'))
16	{
17	    $meta_image = get_wp_term_image($term_id);
18	        //It will give category/term image url
19	}
20
21	echo $meta_image; // category/term image url
22	</pre>
23	where $term_id is 'category/term id'
24
25	= Features =
26	* Setting ,for which taxonomy ,image field is to be enable.
27	* Very simple in use
28	* Can be customized easily.
29
30	== Screenshots ==
31	1. Settings page where you can select the taxonomies you want to include it in WP Custom Taxonomy Image
32	2. Example of the category/taxonomy image under the general category
33
34
35	== Frequently Asked Questions ==
36	1. No technical skills needed.
37
38	== Changelog ==
39	This is first version no known errors found
40
41	== Upgrade Notice ==
42	This is first version no known notices yet
43
44	== Installation ==
45	1. Unzip into your `/wp-content/plugins/` directory. If you're uploading it make sure to upload
46	the top-level folder. Don't just upload all the php files and put them in `/wp-content/plugins/`.
47	2. Activate the plugin through the 'Plugins' menu in WordPress
48	3. Go to your WP-admin ->Settings menu a new "Taxonomy Image" page is created.
49	4. Go to your WP-admin ->Settings ->Taxonomy Image displayed in the taxonomies list form where you can select the taxonomies you want to include it in WP Custom Taxonomy Image.
50	5. Go to your WP-admin select any category/term ,here image text box where you can manage image for that category/term.
51	6. you can use the following function into your templates to get category/term image:
52	<pre>
53	if (function_exists('get_wp_term_image'))
54	{
55	    $meta_image = get_wp_term_image($term_id);
56	        //It will give category/term image url
57	}
58
59	echo $meta_image; // category/term image url
60	</pre>
61	where $term_id is 'category/term id'
62
