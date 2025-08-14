<?php



class Alum
{

	public $states = array(

		'AL' => 'Alabama',

		'AK' => 'Alaska',

		'AZ' => 'Arizona',

		'AR' => 'Arkansas',

		'CA' => 'California',

		'CO' => 'Colorado',

		'CT' => 'Connecticut',

		'DE' => 'Delaware',

		'DC' => 'District of Columbia',

		'FL' => 'Florida',

		'GA' => 'Georgia',

		'HI' => 'Hawaii',

		'ID' => 'Idaho',

		'IL' => 'Illinois',

		'IN' => 'Indiana',

		'IA' => 'Iowa',

		'KS' => 'Kansas',

		'KY' => 'Kentucky',

		'LA' => 'Louisiana',

		'ME' => 'Maine',

		'MD' => 'Maryland',

		'MA' => 'Massachusetts',

		'MI' => 'Michigan',

		'MN' => 'Minnesota',

		'MS' => 'Mississippi',

		'MO' => 'Missouri',

		'MT' => 'Montana',

		'NE' => 'Nebraska',

		'NV' => 'Nevada',

		'NH' => 'New Hampshire',

		'NJ' => 'New Jersey',

		'NM' => 'New Mexico',

		'NY' => 'New York',

		'NC' => 'North Carolina',

		'ND' => 'North Dakota',

		'OH' => 'Ohio',

		'OK' => 'Oklahoma',

		'OR' => 'Oregon',

		'PA' => 'Pennsylvania',

		'RI' => 'Rhode Island',

		'SC' => 'South Carolina',

		'SD' => 'South Dakota',

		'TN' => 'Tennessee',

		'TX' => 'Texas',

		'UT' => 'Utah',

		'VT' => 'Vermont',

		'VA' => 'Virginia',

		'WA' => 'Washington',

		'WV' => 'West Virginia',

		'WI' => 'Wisconsin',

		'WY' => 'Wyoming',

	);



	public $colleges = array(

		'Adelphi University',

		'Agnes Scott College',

		'University of Alabama',

		'University of Alabama Birmingham',

		'University of Alabama Huntsville',

		'Albion College',

		'Albright College',

		'Alfred University',

		'Allegheny College',

		'Amherst College',

		'American International College',

		'American University',

		'Antioch College',

		'University of Arizona',

		'Arizona State University',

		'University of Arkansas',

		'Assumption College',

		'College of the Atlantic',

		'Auburn University',

		'Austin College',

		'Ball State University',

		'Babson College',

		'Bard College',

		'Barnard College',

		'Baruch College, City University of New York',

		'Bates College',

		'Baylor University',

		'Belmont University',

		'Beloit College',

		'Bennington College',

		'Bentley University',

		'Berea College',

		'Birmingham Southern College',

		'Boston College',

		'Boston University',

		'Bowdoin College',

		'Bowling Green State University',

		'Bradley University',

		'Brandeis University',

		'Brigham Young University',

		'University of British Columbia',

		'Brooklyn College',

		'Brown University',

		'Bryant University',

		'Bryn Mawr College',

		'Bucknell University',

		'Butler University',

		'Cal Poly Pomona',

		'Cal Poly San Luis Obispo',

		'University of California - GENERAL',

		'University of California - Berkeley',

		'University of California - Davis',

		'University of California - Irvine',

		'University of California - Los Angeles',

		'University of California - Merced',

		'University of California - Riverside',

		'University of California - San Diego',

		'University of California - Santa Barbara',

		'University of California - Santa Cruz',

		'California Institute of Technology',

		'California Lutheran University',

		'California State University - GENERAL',

		'California State University - Fresno',

		'California State University - Fullerton',

		'California State University - Long Beach',

		'California State University - Los Angeles',

		'California State University - Monterey Bay',

		'California State University - Northridge',

		'Carleton College',

		'Carnegie Mellon University',

		'Carroll College',

		'Case Western Reserve University',

		'Catholic University of America',

		'Central Connecticut State University',

		'University of Central Florida',

		'Centre College',

		'Champlain College',

		'Chapman University',

		'University of Chicago',

		'Christopher Newport University',

		'University of Cincinnati',

		'City College of New York',

		'Claremont McKenna College',

		'Clark University',

		'Clarkson University',

		'Clemson University',

		'Coe College',

		'Colby College',

		'Colgate University',

		'College of Charleston',

		'University of Colorado',

		'Colorado College',

		'Colorado School of Mines',

		'Colorado State University',

		'Columbia College Chicago',

		'Columbia University',

		'University of Connecticut',

		'Connecticut College',

		'Cooper Union',

		'Cornell College',

		'Cornell University',

		'Creighton University',

		'CUNY System',

		'Dartmouth College',

		'Davidson College',

		'University of Dayton',

		'Deep Springs College',

		'University of Delaware',

		'Denison University',

		'University of Denver',

		'DePaul University',

		'DePauw University',

		'Dickinson College',

		'Dominican University of California',

		'Drew University',

		'Drexel University',

		'Duke University',

		'Duquesne University',

		'East Carolina University',

		'Earlham College',

		'Eckerd College',

		'Elon University',

		'Elizabethtown College',

		'Embry-Riddle',

		'Emerson College',

		'Emory University',

		'Fairfield University',

		'Fairleigh Dickinson University',

		'Fashion Institute of Technology',

		'Fisk University',

		'Flagler College',

		'University of Florida',

		'Florida A&M University',

		'Florida Atlantic University',

		'Florida Institute of Technology',

		'Florida International University',

		'Florida State University',

		'Fordham University',

		'Franklin & Marshall College',

		'Furman University',

		'George Mason University',

		'George Washington University',

		'Georgetown University',

		'University of Georgia',

		'Georgia Institute of Technology',

		'Georgia State University',

		'Gettysburg College',

		'Goddard College',

		'Golden Gate University',

		'Gonzaga University',

		'Goucher College',

		'Grinnell College',

		'Grove City College',

		'Gustavus Adolphus College',

		'Hanover College',

		'Hamilton College',

		'Hampden-Sydney College',

		'Hampshire College',

		'Hampton University',

		'University of Hartford',

		'Hartwick College',

		'Harvard University',

		'Harvey Mudd College',

		'Haverford College',

		'Hendrix College',

		'High Point University',

		'Hillsdale College',

		'Hiram College',

		'Hobart and William Smith Colleges',

		'Hofstra University',

		'Hollins University',

		'College of the Holy Cross',

		'Hope College',

		'University of Houston',

		'Howard University',

		'Humboldt State University',

		'University of Illinois - Chicago',

		'University of Illinois - Urbana-Champaign',

		'Indiana University - Bloomington',

		'Indiana University of Pennsylvania',

		'Indiana University-Purdue University Indianapolis',

		'Indiana University - South Bend',

		'University of Iowa',

		'Iowa State University',

		'Illinois Wesleyan University',

		'Illinois Institute of Technology',

		'Ithaca College',

		'James Madison University ',

		'Johns Hopkins University ',

		'The Juilliard School ',

		'Juniata College',

		'Kalamazoo College ',

		'University of Kansas ',

		'Kansas State University ',

		'Kent State University ',

		'University of Kentucky ',

		'Kenyon College ',

		'Knox College',

		'Lafayette College',

		'Lake Forest College',

		'LaSalle University',

		'Lawrence University',

		'Lehigh University',

		'Lewis & Clark College',

		'Linfield College',

		'Louisiana State University',

		'Loyola University of Chicago',

		'Loyola University - Maryland',

		'Loyola University - New Orleans',

		'Loyola Marymount University',

		'Lynchburg College',

		'Macalester College',

		'University of Maine',

		'Manhattan College',

		'Manhattanville College',

		'Marietta College',

		'Marist College',

		'Marlboro College',

		'Marquette University',

		'University of Mary Washington',

		'University of Maryland - Baltimore County',

		'University of Maryland - College Park',

		'Marymount Manhattan College',

		'University of Massachusetts - Amherst',

		'University of Massachusetts - Boston',

		'Massachusetts Institute of Technology',

		'McGill University',

		'Mercer University',

		'Merrimack College',

		'University of Miami - Florida',

		'Miami University - Ohio',

		'University of Michigan',

		'Michigan State University',

		'Michigan Technological University',

		'Middlebury College',

		'Mills College',

		'Millsaps College',

		'University of Minnesota',

		'University of Mississippi',

		'Mississippi State University',

		'University of Missouri - Columbia',

		'University of Missouri - Kansas City',

		'Missouri University of Science and Technology',

		'Monmouth University',

		'University of Montana',

		'Montclair State University',

		'Moravian College',

		'Morehouse College',

		'Mount Holyoke College',

		'Muhlenberg College',

		'University of Nebraska',

		'University of Nevada',

		'New College of Florida',

		'New England College',

		'University of New Hampshire',

		'University of New Haven',

		'The College of New Jersey',

		'New Jersey Institute of Technology',

		'New Mexico Institute of Mining & Technology',

		'University of New Mexico',

		'University of New Orleans',

		'New York University',

		'University of North Carolina - Asheville',

		'University of North Carolina - Chapel Hill',

		'University of North Carolina - Charlotte',

		'University of North Carolina - Greensboro',

		'University of North Carolina - Wilmington',

		'North Carolina School of the Arts',

		'North Carolina State University',

		'University of North Dakota',

		'University of North Florida',

		'Northeastern University',

		'Northern Arizona University',

		'Northwestern University',

		'University of Notre Dame',

		'Oberlin College',

		'Occidental College',

		'Oglethorpe University',

		'Ohio University',

		'Ohio Northern University',

		'Ohio State University',

		'Ohio Wesleyan University',

		'University of Oklahoma',

		'Oklahoma State University',

		'Old Dominion University',

		'Olin College',

		'University of Oregon',

		'Oregon State University',

		'Pace University',

		'University of the Pacific',

		'Parsons School of Design',

		'Penn State',

		'University of Pennsylvania',

		'Pepperdine University',

		'Philadelphia University',

		'University of Pittsburgh',

		'Pitzer College',

		'Pomona College',

		'Pratt Institute',

		'Princeton University',

		'Providence College',

		'University of Puget Sound',

		'Purdue University',

		'Queens University - Kingston, Ontario Quinnipiac University',

		'Randolph College',

		'Randolph-Macon College',

		'University of Redlands',

		'Reed College',

		'Regis University',

		'Rensselaer Polytechnic Institute',

		'University of Rhode Island',

		'Rhode Island School of Design',

		'Rhodes College',

		'Rice University',

		'University of Richmond',

		'Rider University',

		'Ripon College',

		'University of Rochester',

		'Rochester Institute of Technology',

		'Rollins College',

		'Rose-Hulman Institute of Technology',

		'Rowan University',

		'Rutgers University',

		'Sacred Heart University',

		'College of Saint Benedict and Saint John\'s University',

		'St. Bonaventure University',

		'St. John\'s College - Maryland',

		'St. John\'s College - New Mexico',

		'St. John\'s University (New York)',

		'Saint Joseph\'s University',

		'St. Lawrence University',

		'Saint Louis University',

		'Saint Mary\'s College of California',

		'St. Mary\'s College of Maryland',

		'Saint Mary\'s College - Notre Dame',

		'Saint Michael\'s College',

		'Saint Olaf College',

		'Salisbury University',

		'Salve Regina University',

		'Samford University',

		'University of San Diego',

		'San Diego State University',

		'San Francisco State University',

		'University of San Francisco',

		'San Jose State University',

		'Santa Clara University',

		'Sarah Lawrence College',

		'Savannah College of Art and Design',

		'School of Visual Arts',

		'University of Scranton',

		'Saint Anselm College',

		'Scripps College',

		'Seattle University',

		'Seton Hall University',

		'Sewanee: The University of the South',

		'Shimer College',

		'Siena College',

		'Simmons College',

		'Simon\'s Rock College of Bard',

		'Skidmore College',

		'Smith College',

		'Soka University of America',

		'Sonoma State University',

		'University of South Carolina',

		'University of South Florida',

		'University of Southern California',

		'Southern Methodist University',

		'Southwestern University',

		'Spelman College',

		'Stanford University',

		'Stephens College',

		'Stetson University',

		'Stevens Institute of Technology',

		'Stevenson University',

		'Stonehill College',

		'SUNY - General',

		'SUNY at Albany',

		'SUNY at Binghamton',

		'SUNY at Buffalo',

		'SUNY at Geneseo',

		'SUNY at Purchase',

		'SUNY at Stony Brook',

		'Susquehanna University',

		'Swarthmore College',

		'Sweet Briar College',

		'Syracuse University',

		'University of Tampa',

		'Temple University',

		'University of Tennessee',

		'The University of Texas - GENERIC',

		'The University of Texas at Austin',

		'Texas A&M University',

		'TCU',

		'Texas Tech University',

		'Thomas Aquinas College - California',

		'University of Toronto',

		'Towson University',

		'Trinity College (Connecticut)',

		'Trinity University (Texas)',

		'Truman State University',

		'Tufts University',

		'Tulane University',

		'University of Tulsa',

		'Tuskegee University',

		'Union College - New York',

		'United States Air Force Academy',

		'United States Coast Guard Academy',

		'United States Merchant Marine Academy',

		'United States Military Academy',

		'United States Naval Academy',

		'University of the Sciences in Philadelphia',

		'Ursinus College',

		'Ursuline College',

		'Valparaiso University',

		'Vanderbilt University',

		'Vassar College',

		'University of Vermont',

		'Villanova University',

		'University of Virginia',

		'Virginia Commonwealth University',

		'Virginia Tech',

		'Virginia Wesleyan College',

		'Wabash College',

		'Wagner College',

		'Wake Forest University',

		'Warren Wilson College',

		'University of Washington',

		'Washington and Lee University',

		'Washington College',

		'Washington State University',

		'Washington University in St. Louis',

		'University of Waterloo',

		'Webb Institute',

		'Wellesley College',

		'Wells College',

		'Wesleyan College',

		'Wesleyan University',

		'West Virginia University',

		'Western Michigan University',

		'Western Washington University',

		'Westminster College - Pennsylvania',

		'Wheaton College - Illinois',

		'Wheaton College - Massachusetts',

		'Whitman College',

		'Whittier College',

		'College of William and Mary',

		'Willamette University',

		'William Jewell College',

		'Williams College',

		'University of Wisconsin',

		'Wittenberg University',

		'Wofford College',

		'College of Wooster',

		'Worcester Polytechnic Institute',

		'Wright State University',

		'University of Wyoming',

		'Yale University ',

		'Yeshiva University ',

		'Xavier University'

	);



	public $professions = array(

		'Accounting' => array(

			'Accountant',

			'Accounting Manager',

			'Appraiser',

			'Assessor',

			'Auditor',

			'Banker',

			'Banking Analyst',

			'Banking Manager',

			'Bookkeeper',

			'Budget Analyst',

			'Certified Public Accountant',

			'Chief Financial Officer',

			'Compensation or Benefits Specialist',

			'Controller',

			'Cost Estimator',

			'Credit Analyst',

			'Financial Analyst',

			'Financial Examiner',

			'Financial Manager',

			'Financial Manager, Branch or Department',

			'Financial Planner',

			'Financial Specialist',

			'Loan Counselor',

			'Loan Officer',

			'Mortgage Analyst',

			'Mortgage Broker',

			'Personal Financial Advisor',

			'Revenue Agent',

			'Risk Analyst',

			'Tax Collector',

			'Tax Examiner',

			'Tax Manager',

			'Tax Preparer',

			'Treasurer',

			'Underwriter'

		),

		'General Business' => array(),

		'Other' => array(),

		'Administrative & Clerical' => array(

			'Accounting Clerk',

			'Adjustment Clerk',

			'Answering Service Operator',

			'Auditing Clerk',

			'Bill &amp; Account Collector',

			'Billing, Cost or Rate Clerk',

			'Billing, Posting or Calculating Machine Operator',

			'Bookkeeping Clerk',

			'Brokerage Clerk',

			'Central Office Operator',

			'Communication Equipment Operator',

			'Correspondence Clerk',

			'Court Clerk',

			'Credit Authorizer',

			'Credit Checker',

			'Directory Assistance Operator',

			'Executive Secretary',

			'File Clerk',

			'Information &amp; Record Clerk',

			'Information Clerk',

			'Legal Secretary',

			'License Clerk',

			'Loan Interviewer',

			'Mail Clerk',

			'Mail Machine Operator',

			'Marking Clerk',

			'Medical Secretary',

			'Municipal Clerk',

			'New Accounts Clerk',

			'Office Clerk',

			'Order Clerk',

			'Order Filler',

			'Payroll &amp; Timekeeping Clerk',

			'Postal Service Clerk',

			'Postal Service Mail Carrier',

			'Postal Service Mail Processor',

			'Procurement clerk',

			'Receptionist',

			'Secretary',

			'Statement Clerk',

			'Stock Clerk',

			'Switchboard Operator',

			'Teller',

			'Warehouse Clerk',

			'Word Processor or Typist'

		),

		'General Labor' => array(),

		'Pharmaceutical' => array(

			'Biochemist',

			'Biological Engineer',

			'Biological Scientist',

			'Biological Technician',

			'Chemical Technician',

			'Chemist',

			'Fabricator',

			'Inspector',

			'Laboratory Assistant',

			'Medical Scientist',

			'Microbiologist',

			'Organic Chemist',

			'Pharmacist',

			'Pharmacologist',

			'Pharmacy Aide',

			'Pharmacy Assistant',

			'Pharmacy Technician'

		),

		'Automotive' => array(),

		'Government' => array(),

		'Professional Services' => array(),

		'Banking' => array(

			'Credit Analyst',

			'Loan Officer',

			'Branch Manager',

			'Trust officer',

			'Mortgage Banker',

			'Private Banking',

			'Loan Servicing',

			'Analyst/Associate',

			'Equity/Credit Research',

			'Institutional Sales',

			'Trading',

			'Structured Finance',

			'Emerging Markets',

			'Public Finance',

			'Credit/Ratings Analyst',

			'Broker/Private Client',

			'IT/Systems',

			'Capital Markets'

		),

		'Grocery' => array(),

		'Purchasing - Procurement' => array(),

		'Science & Biotech' => array(

			'Agricultural Technician',

			'Animal Scientist',

			'Astronomer',

			'Atmospheric Scientist',

			'Biochemist',

			'Biological Technician',

			'Biologist',

			'Biophysicist',

			'Biostatatician',

			'Chemical Technician',

			'Chemist',

			'City Planning Aides',

			'Clinical Data Management',

			'Clinical In-house Monitoring',

			'Clinical Pharmacovigilance/Drug Safety',

			'Clinical Quality Assurance Officer',

			'Clinical Regional Monitoring',

			'Clinical Regulatory Affairs',

			'Clinical Research Associate',

			'Clinical SAS Programming',

			'Environmental Science Technician',

			'Environmental Scientist',

			'Epidemiologist',

			'Food Science Technician',

			'Food Scientist',

			'Forest &amp; Conservation Technician',

			'Forester',

			'Geological Data Technician',

			'Geological Sample Test Technician',

			'Geologist',

			'Hydrologist',

			'Laboratory Technician',

			'Life Scientist',

			'Market Research Analyst',

			'Materials Scientist',

			'Medical Scientist',

			'Microbiologist',

			'Nuclear Technician',

			'Park Naturalist',

			'Physical Scientist',

			'Physicist',

			'Plant Scientist',

			'Social Science Research Assistant',

			'Social Scientist',

			'Soil Conservationist',

			'Soil Scientist',

			'Space Scientist',

			'Survey Researcher',

			'Wildlife Biologist',

			'Zoologist'

		),

		'Healthcare & Medical' => array(

			'Admissions Director',

			'Admissions Registration Clerk',

			'Athletic Trainer',

			'Billing/Coding Specialist',

			'Cardiovascular Technologist',

			'Case Management Manager',

			'Chart Assembler',

			'Coding Educator',

			'Dental Assistant',

			'Diagnostic Medical Sonographer',

			'Diagnostic Medical Nutritionist',

			'Emergency Medical Technician',

			'Emergency Wound Care Technician',

			'Health Technologist',

			'Home Health Aide',

			'Laboratory Animal Caretaker',

			'Licensed Nurse',

			'Materials Manager',

			'Medical &amp; Clinical Laboratory Technician',

			'Medical &amp; Clinical Laboratory Technologist',

			'Medical Assistant',

			'Medical Equipment Preparer',

			'Medical Equipment Repairer',

			'Medical Office Manager',

			'Medical or Health Services Manager',

			'Medical Records Assistant',

			'Medical Records Technician',

			'Medical Secretaries',

			'Medical Transcriptionist',

			'Message Therapist',

			'Nursing Aide',

			'Occupational Health &amp; Safety Specialist',

			'Occupational Therapist Aide',

			'Orderly',

			'Pharmacist',

			'Pharmacy Technician',

			'Physical Therapist',

			'Physician Assistant',

			'Psychiatric Aide',

			'Quality Assurance Director',

			'Quality Coordinator',

			'Radiologic Technician',

			'Radiologic Technologist',

			'Registered Nurse',

			'Surgical Technologist',

			'Veterinary Assistant',

			'Veterinary Technician'

		),

		'QA - Quality Control' => array(),

		'Hotel - Hospitality' => array(),

		'Real Estate' => array(

			'Agent/Broker',

			'Commercial Broker',

			'Appraiser',

			'Property Management',

			'Advisory/Analyst',

			'Investment Banking',

			'Development',

			'Construction Management'

		),

		'Business Development' => array(),

		'Human Resources' => array(

			'Administrative Assistant',

			'Benefits Administrator',

			'Benefits Representative',

			'Compliance Analyst',

			'Compliance Coordinator',

			'Compliance Manager',

			'Compliance Officer',

			'Employee Relations',

			'Human Resources Assistant',

			'Human Resources Benefits Specialists',

			'Human Resources Compensation Representative',

			'Human Resources Compliance Specialist',

			'Human Resources Coordinator',

			'Human Resources Customer Service Representative',

			'Human Resources Director',

			'Human Resources Generalist',

			'Human Resources Manager',

			'Human Resources Recruitment Coordinator',

			'Human Resources Supervisor',

			'Labor Relations Manager',

			'Sourcing Specialist'

		),

		'Construction' => array(

			'Collections Agent',

			'Customer Service Manager',

			'Customer Service Representative',

			'Customer Service Supervisor',

			'Facilities Manager',

			'Sales Assistant'

		),

		'Information Technology' => array(

			'Business Analyst',

			'Business-to-Business Marketer',

			'Computer Operator',

			'Computer Programmer',

			'Database Administrator',

			'Database Designer',

			'Datawarehouse Designer',

			'ERP',

			'Help Desk Specialist',

			'Network Administrator',

			'Network Architect',

			'Network Engineer',

			'Network Installer',

			'Project Manager',

			'Quality Assurance Specialist',

			'Software Engineer',

			'System Administrator',

			'System Engineer',

			'Technical Recruiter',

			'Technical Recruiter Consultant',

			'Technical Trainer',

			'Tech Writers',

			'Telecomm Specialist',

			'Tester',

			'Web Developer',

			'Webmaster'

		),

		'Restaurant - Food Service' => array(),

		'Consultant' => array(

			'Management/Strategic',

			'Government Contracts',

			'Healthcare',

			'Finance',

			'Litigation'

		),

		'Installation - Maint - Repair' => array(),

		'Retail' => array(

			'Management Trainee',

			'Store Manager',

			'Buyer',

			'Department Manager',

			'Sales',

			'Regional Manager'

		),

		'Customer Service' => array(),

		'Insurance' => array(

			'Actuary',

			'Agent/Broker',

			'Claims Adjuster',

			'Loss Control',

			'Underwriter',

		),

		'Sales' => array(

			'Advertising Sales Agent',

			'Agricultural Sales Representative',

			'Biotechnical Equipment Sales Representative',

			'Cashier',

			'Chemical Sales Representative',

			'Computer Hardware Sales Representative',

			'Counter Clerk',

			'Demonstrator',

			'Door-to-door Sales Worker',

			'Financial Services Sales Agent',

			'Food Service Sales Agent',

			'Instrument Sales Representative',

			'Insurance Sales Agent',

			'Mechanical Equipment Sales Representative',

			'Medical Sales Representative',

			'Non-retail Sales Supervisor',

			'Parts Salesperson',

			'Pharmaceutical Sales Representative',

			'Product Promoter',

			'Real Estate Sales Agent',

			'Retail Sales Representative',

			'Retail Sales Supervisor',

			'Sales Engineer Position',

			'Sales &amp; Sales Management',

			'Software Sales Representative',

			'Telecommunications Sales Agent',

			'Telemarketer',

			'Vendor'

		),

		'Media & Communication' => array(

			'Audio &amp; Video Equipment Technician',

			'Broadcast News Analyst',

			'Broadcast Technician',

			'Camera Operator',

			'Caption Writer',

			'Copy Writing',

			'Correspondent',

			'Creative Writer',

			'Editor',

			'Film &amp; Video Editor',

			'Grip',

			'Lyricist',

			'Media &amp; Communication Equipment Operator',

			'Motion Picture Set Worker',

			'Poet',

			'Professional Photographer',

			'Public Address System Announcer',

			'Public Relations Specialist',

			'Radio Announcer',

			'Radio Operator',

			'Reporter',

			'Sound Engineering Technician',

			'Stage Hand',

			'Studio Worker',

			'Media and Communication Technical Writer',

			'Television Announcer'

		),

		'Design' => array(),

		'Inventory' => array(),

		'Distribution - Shipping' => array(),

		'Legal Services' => array(

			'Attorney',

			'Adjudicator',

			'Administrative Law Judge',

			'Arbitrator, Mediator or Conciliator',

			'Collector',

			'Contract Administrator',

			'Coroner',

			'Court Clerk',

			'Court Reporter',

			'Document Analyst',

			'Environmental Compliance Inspector',

			'Equal Opportunity Representative',

			'Government Property Inspector/Investigator',

			'Hearing Officer',

			'Law Clerk',

			'Law Office Administrator',

			'Legal Assistant',

			'Legal Secretary',

			'Legal Support Worker',

			'Licensing Examiner/Inspector',

			'Occupational Health &amp; Safety Specialist',

			'Paralegal',

			'Patent Administrator',

			'Practice Support Manager',

			'Pressure Vessel Inspector',

			'Revenue Agent',

			'Tax Examiner',

			'Title Examiner &amp; Abstractor',

			'Title Searcher'

		),

		'Skilled Labor - Trades' => array(),

		'Education - Teaching' => array(

			'Adult Literacy Instructor',

			'Archivists',

			'Area, Ethnic, &amp; Cultural Studies Teacher',

			'Audio-visual Collections Specialist',

			'Biological Science Teacher',

			'Business Teacher',

			'Cataloger',

			'Communications Teacher',

			'Computer Science Teacher',

			'Curator',

			'Education Teacher',

			'Educational Administrator',

			'Elementary &amp; Secondary School Administrator',

			'Elementary School Teacher',

			'English Language &amp; Literature Teacher',

			'Foreign Language &amp; Literature Teacher',

			'GED Instructor',

			'Graduate Teaching Assistant',

			'Health Specialties Teacher',

			'History Teacher',

			'Instructional Coordinator',

			'Kindergarten Teacher',

			'Librarian',

			'Library Assistant - Clerical',

			'Library Technician',

			'Mathematical Science Teacher',

			'Middle School Teacher',

			'Museum Conservator',

			'Postsecondary School Administrator',

			'Postsecondary Teacher',

			'Preschool or Child Care Center Administrator',

			'Preschool Teacher',

			'Psychology Teacher',

			'Remedial Education Instructor',

			'School Counselor',

			'Secondary School Teacher',

			'Self-enrichment Instructor',

			'Social Science Teacher',

			'Special Education Teacher',

			'Special Education Teacher - Elementary School',

			'Special Education Teacher - Kindergarten',

			'Special Education Teacher - Middle School',

			'Special Education Teacher - Preschool',

			'Teaching Assistant',

			'Vocational Education School Counselor',

			'Vocational Education Teacher - Middle School',

			'Vocational Education Teacher - Postsecondary',

			'Vocational Education Teacher - Secondary School'

		),

		'Strategy - Planning' => array(),

		'Engineering' => array(

			'Aerospace Engineer',

			'Agricultural Engineering',

			'Automotive Engineer',

			'Biomedical Engineer',

			'Chemical Engineer',

			'Civil Engineer',

			'Computer Engineer',

			'Drafting and Design Engineer',

			'Electrical Engineer',

			'Environmental Engineer',

			'Geological Engineer',

			'Marine Engineer',

			'Mechanical Engineer',

			'Petroleum Engineer',

			'Software Engineer'

		),

		'Management & Business' => array(

			'Administrative Services Manager',

			'Advertising Manager',

			'Assistant Director',

			'Chief communications Officer',

			'Chief Executive Office',

			'Chief Financial Officer',

			'Chief Information Officer',

			'Chief Marketing Officer',

			'Chief Operations Office',

			'Chief Resources Officer',

			'Chief Security Officer',

			'Chief Technology Officer',

			'Compensation &amp; Benefits Manager',

			'Construction manager',

			'Director',

			'Distribution manager',

			'Engineering Manager',

			'Executive Director',

			'Executive Vice President',

			'Financial Manager',

			'Food Service Manager',

			'Gaming Manager',

			'General Counsel',

			'General Manager',

			'Government Service Executive',

			'Human Resources Manager',

			'Industrial Production Manager',

			'Information Technology Manager',

			'Management Trainee',

			'Manager',

			'Managing Director',

			'Marketing manager',

			'Medical &amp; Health Services Manager',

			'Partner',

			'President',

			'Principal',

			'Program Manager',

			'Public Relations Manager',

			'Real Estate Manager',

			'Regional/Multi-Unit Manager',

			'Restaurant Management',

			'Revenue Management',

			'Sales Manager',

			'Security Management',

			'Senior Vice President',

			'Supervisor',

			'Training &amp; Development Manager',

			'Transportation Manager',

			'Vice President'

		),

		'Supply Chain' => array(),

		'Entry Level - New Grad' => array(),

		'Manufacturing & Production' => array(

			'Aircraft Rigging Assembler',

			'Aircraft Structure Assembler - Precision',

			'Aircraft Systems Assemblers - Precision',

			'Assembler',

			'Camera Operator',

			'Chemical Equipment Controller',

			'Chemical Equipment Operator',

			'Chemical Equipment Tender',

			'Chemical Plant System Operator',

			'Coating, Painting, or Spraying Machine Operator',

			'Cutting &amp; Slicing Machine Operator',

			'Electrical &amp; Electronic Equipment Assembler',

			'Electrical Inspector',

			'Electrical Tester',

			'Electromechanical Equipment Assembler',

			'Engine &amp; Other Machine Assembler',

			'Extruding Machine Operator',

			'Fabricator',

			'Food Batchmaker',

			'Grinding Machine Set-up Operator',

			'Hand Grinding/Polishing Worker',

			'Machinist',

			'Manufacturing Baker',

			'Materials Inspector',

			'Mechanical Inspector',

			'Medical Appliance Technician',

			'Metal Fabricator, Structural Metal Products',

			'Optical Instrument Assembler',

			'Production Painter',

			'Petroleum Pump System Operator',

			'Petroleum Refinery Operator',

			'Plant Operator',

			'Plastic Molding &amp; Casting Machine Operator',

			'Precision Device Inspector',

			'Precision Device Tester',

			'Press &amp; Press Brake Machine Setter',

			'Printing Machine Operator',

			'Production Helpers',

			'Production Inspector',

			'Production Laborers',

			'Production Sampler',

			'Production Sorter',

			'Production Tester',

			'System Operator',

			'Team Assembler',

			'Woodworker'

		),

		'Telecommunications' => array(

			'Telephone Operator Jobs',

			'Switchboard Operator Jobs',

			'Telecommunications Technician Jobs',

			'Telephone Sales Jobs',

			'Telephone Technician Jobs',

			'Tower Hand Jobs',

			'Telecommunications Management Jobs',

			'Telecommunications Manager Jobs',

			'Technology Manager Jobs',

			'Telecommunications Analyst Jobs',

			'Telecommunications Specialist Jobs',

			'Wireless Consultant Jobs',

			'Satellite Communications Technician Jobs',

			'Telecommunications Service Specialist Jobs',

			'Telecommunications Billing Analyst Jobs',

			'Wireless Installer Jobs',

			'Telecommunications Coordinator Jobs',

			'PBX Installer Jobs',

			'Telecommunications Supervisor Jobs',

			'Telecommunications Systems Jobs',

			'Telephony Engineer Jobs',

			'Telecommunications Equipment Installer and Repairer Jobs',

			'Telephone Operator Ii Jobs',

			'Telecommunications Line Installer and Repairer Jobs',

			'Telecommunications Systems Director Jobs',

			'Switchboard Services Manager Jobs',

			'Telecommunications Technicians/Installers Jobs',

			'Telecommunications Service Tester Jobs',

			'Telephone Line Apprentice Jobs',

			'Telecommunications Site Switch Technician Jobs'

		),

		'Executive' => array(),

		'Marketing & Advertising' => array(

			'Account Assistant',

			'Account Executive',

			'Account Manager',

			'Advertising Sales Agents',

			'Animator',

			'Advertising Art Director',

			'Brand Management Specialist',

			'Consumer Behavior Analyst',

			'Copywriter',

			'Creative Director',

			'Database Marketing Specialist',

			'Demonstrators',

			'Direct Marketer',

			'Email Marketing Specialist',

			'Event and Trade Show Marketer',

			'Field Marketing Representative',

			'Global Marketing Associate',

			'Graphic Designer',

			'Integrated Marketing Associate',

			'Interactive Marketing Specialist',

			'Internet Marketing Specialist',

			'Market Analyst',

			'Market Development Specialist',

			'Market Research Analyst',

			'Market Researcher',

			'Marketing Communications Associate',

			'Marketing Coordinator',

			'Media Buyer',

			'Media Director',

			'Media Planner',

			'Retail Merchandise Displayer',

			'Multi-Media Artist',

			'Office and Administrative Assistants',

			'Product Development Associate',

			'Product Marketing Associate',

			'Product Promoters',

			'Production Worker',

			'Promotions Manager',

			'Purchasing Agent',

			'Research Executive',

			'Retention Marketing Specialist',

			'SEO Specialist',

			'Survey Researcher',

			'Telemarketer',

			'Viral or WOM Marketing Specialist',

		),

		'Training' => array(),

		'Facilities' => array(),

		'Media - Journalism - Newspaper' => array(),

		'Transportation & Logistics' => array(

			'Able Seamen',

			'Air Traffic Controller',

			'Aircraft Cargo Handler',

			'Airfield Operations Specialist',

			'Airline Copilot',

			'Airline Pilot',

			'Aviation Inspector',

			'Bridge or Lock Tender',

			'Chauffeur',

			'Commercial Pilot',

			'Conveyor Operator',

			'Crane Operator',

			'Delivery Services Truck Driver',

			'Excavating Machine Operator',

			'Flight Engineer',

			'Freight Inspector',

			'Hand Freight Mover',

			'Heavy Truck Driver',

			'Hoist &amp; Winch Operator',

			'Industrial Truck Operator',

			'Loading Machine Operator',

			'Locomotive Engineer',

			'Machine Feeder',

			'Marine Cargo Inspector',

			'Mates',

			'Motor Vehicle Inspector',

			'Motor Vehicle Operator',

			'Motorboat Operator',

			'Ordinary Seaman',

			'Packer',

			'Public Transportation Inspector',

			'Rail Car Repairer',

			'Rail Yard Engineer',

			'Railroad Conductor',

			'Railroad Yard Worker',

			'Refuse Material Collector',

			'School Bus Driver',

			'Ship Captain',

			'Shuttle Car Operator',

			'Streetcar Operator',

			'Subway Operator',

			'Taxi Driver',

			'Truck Driver',

			'Transit Bus Driver',

			'Yardmaster'

		),

		'Finance' => array(),

		'Nonprofit - Social Services' => array(),

		'Retired' => array()

	);



	public function getUserData($user_id)
	{

		global $wpdb;

		$sql = "SELECT * FROM alumni WHERE user_id = '$user_id' LIMIT 1";

		$user_data = $wpdb->get_row($sql);

		//Avatar

		$avatar = get_user_meta($user_id, '_alum_avatar', true);

		if ($avatar != '') {

			$user_data->avatar = $avatar;
		}

		$user_info = get_userdata($user_id);

		if ($user_data->email == '' && $user_info->user_email != '') {

			$user_data->email = $user_info->user_email;
		}

		if ($user_data->date_joined == '' && $user_info->user_registered != '') {

			$user_data->date_joined = $user_info->user_registered;

			$wpdb->update(

				'alumni',

				array(

					'date_joined' => $user_info->user_registered,

				),

				array('user_id' => $user_id),

				array(

					'%s'

				),

				array('%d')

			);
		}

		//Extra

		$user_data->display_name = $user_info->display_name;

		$user_data->birthdate = get_user_meta($user_data->user_id, '_alum_birthdate', true);

		$user_data->employer_name = get_user_meta($user_data->user_id, '_alum_employer_name', true);

		$user_data->employer_address = get_user_meta($user_data->user_id, '_alum_employer_address', true);

		$user_data->spouse_name = get_user_meta($user_data->user_id, '_alum_spouse_name', true);

		//$user_data->mobile_phone = get_user_meta($user_data->user_id, '_alum_mobile_phone', true);

		$user_data->mobile_phone = $user_data->phone;

		$user_data->home_phone = get_user_meta($user_data->user_id, '_alum_home_phone', true);

		$user_data->work_phone = get_user_meta($user_data->user_id, '_alum_work_phone', true);



		$user_data->are_you_hiring = get_user_meta($user_data->user_id, '_alum_are_you_hiring', true);

		$user_data->hiring_position = get_user_meta($user_data->user_id, '_alum_hiring_position', true);

		$user_data->seeking_employment = get_user_meta($user_data->user_id, '_alum_seeking_employment', true);

		$user_data->type_employment_seeking = get_user_meta($user_data->user_id, '_alum_type_employment_seeking', true);



		return $user_data;
	}



	public function getUserDataByID($id)
	{

		global $wpdb;

		//echo "P";

		$sql = "SELECT * FROM alumni WHERE id = '$id' LIMIT 1";

		//echo $sql;

		$user_data = $wpdb->get_row($sql);

		//echo "T<pre>"; print_r($user_data);

		//Avatar

		$avatar = get_user_meta($user_data->user_id, '_alum_avatar', true);

		if ($avatar != '') {

			$user_data->avatar = $avatar;
		}



		return $user_data;
	}



	public function getSettings($user_id)
	{

		global $wpdb;

		$user_settings['email'] = get_user_meta($user_id, '_alum_email_privacy', true);

		$user_settings['phone'] = get_user_meta($user_id, '_alum_phone_privacy', true);

		$user_settings['address'] = get_user_meta($user_id, '_alum_address_privacy', true);

		$user_settings['city'] = get_user_meta($user_id, '_alum_city_privacy', true);

		$user_settings['state'] = get_user_meta($user_id, '_alum_state_privacy', true);

		$user_settings['zipcode'] = get_user_meta($user_id, '_alum_zipcode_privacy', true);

		$user_settings['optin'] = get_user_meta($user_id, '_alum_optin', true);

		$user_settings['event_notifications_setting'] = get_user_meta($user_id, '_alum_event_notifications_setting', true);

		$user_settings['group_messages_notifications_setting'] = get_user_meta($user_id, '_alum_group_messages_notifications_setting', true);

		$user_settings['pledge_messages_notifications_setting'] = get_user_meta($user_id, '_alum_pledge_messages_notifications_setting', true);



		return $user_settings;
	}





	public function saveSettings($user_id)
	{

		global $wpdb;

		//print_r($_REQUEST);

		//exit;

		$email_privacy = ($_REQUEST['email_privacy'] == '') ? '0' : $_REQUEST['email_privacy'];

		$phone_privacy = ($_REQUEST['phone_privacy'] == '') ? '0' : $_REQUEST['phone_privacy'];

		$address_privacy = ($_REQUEST['address_privacy'] == '') ? '0' : $_REQUEST['address_privacy'];

		$city_privacy = ($_REQUEST['city_privacy'] == '') ? '0' : $_REQUEST['city_privacy'];

		$state_privacy = ($_REQUEST['state_privacy'] == '') ? '0' : $_REQUEST['state_privacy'];

		$zipcode_privacy = ($_REQUEST['zipcode_privacy'] == '') ? '0' : $_REQUEST['zipcode_privacy'];

		$optin_privacy = ($_REQUEST['optin'] == '') ? '0' : $_REQUEST['optin'];

		$event_notifications_setting = ($_REQUEST['event_notifications'] == '') ? '0' : $_REQUEST['event_notifications'];

		$group_messages_notifications_setting = ($_REQUEST['group_messages_notifications'] == '') ? '0' : $_REQUEST['group_messages_notifications'];

		$pledge_messages_notifications_setting = ($_REQUEST['pledge_messages_notifications'] == '') ? '0' : $_REQUEST['pledge_messages_notifications'];



		//echo "USER: $user_id - EMAIL: " . $email_privacy . "<BR>";

		//exit;

		update_user_meta($user_id, '_alum_email_privacy', $email_privacy);

		update_user_meta($user_id, '_alum_phone_privacy', $phone_privacy);

		update_user_meta($user_id, '_alum_address_privacy', $address_privacy);

		update_user_meta($user_id, '_alum_city_privacy', $city_privacy);

		update_user_meta($user_id, '_alum_state_privacy', $state_privacy);

		update_user_meta($user_id, '_alum_zipcode_privacy', $zipcode_privacy);

		update_user_meta($user_id, '_alum_optin', $optin_privacy);

		update_user_meta($user_id, '_alum_event_notifications_setting', $event_notifications_setting);

		update_user_meta($user_id, '_alum_group_messages_notifications_setting', $group_messages_notifications_setting);

		update_user_meta($user_id, '_alum_pledge_messages_notifications_setting', $pledge_messages_notifications_setting);

		//Password..

		if ($_REQUEST['password'] != '') {

			wp_set_password($_REQUEST['password'], $user_id);
		}

		return true;
	}



	public function updateProfile($user_id)
	{

		global $wpdb;

		// $request = stripslashes($_REQUEST);
		$request = $_REQUEST;

		$user_id = $request['user_id'];

		$email = $request['email'];

		//$phone= $request['phone'];

		$address = $request['address'];

		$city = $request['city'];

		$state = $request['state'];

		$zipcode = $request['zipcode'];

		$occupation = $request['occupation'];

		$occupation2 = $request['occupation2'];

		$occupation_description = $request['occupation_description'];

		$display_name = $request['display_name'];

		$username = $request['username'];

		$group_id = $request['group_id'];

		$employer_name = $request['employer_name'];

		$employer_address = $request['employer_address'];

		$birthdate = $request['birthdate'];

		$spouse_name = $request['spouse_name'];

		$phone = $request['mobile_phone'];

		$home_phone = $request['home_phone'];

		$work_phone = $request['work_phone'];



		$are_you_hiring = $request['are_you_hiring'];

		$hiring_position = $request['hiring_position'];

		$seeking_employment = $request['seeking_employment'];

		$type_employment_seeking = $request['type_employment_seeking'];


		$log = print_r($_REQUEST, true);
		$log .= "Request is: " . print_r($request, true) . "\n";
		$log .= "User ID: " . $user_id . "\n";
		$log .= "Display Name: " . $display_name . "\n";


		$wpdb->update(

			'alumni',

			array(

				'email' => $email,

				'phone' => $phone,

				'address' => $address,

				'city' => $city,

				'state' => $state,

				'zipcode' => $zipcode,

				'occupation' => $occupation,

				'occupation2' => $occupation2,

				'occupation_description' => $occupation_description,

				'last_updated' => date("Y-m-d H:i:s"),

			),

			array('user_id' => $user_id),

			array(

				'%s',

				'%s',

				'%s',

				'%s',

				'%s',

				'%s',

				'%s',

				'%s',

				'%s',

				'%s',

			),

			array('%d')

		);

		//$wpdb->show_errors(); 

		//$wpdb->print_error();



		//Extra Fields

		update_user_meta($user_id, '_alum_birthdate', $birthdate);

		update_user_meta($user_id, '_alum_employer_name', $employer_name);

		update_user_meta($user_id, '_alum_employer_address', $employer_address);

		update_user_meta($user_id, '_alum_spouse_name', $spouse_name);

		//update_user_meta( $user_id, '_alum_mobile_phone', $mobile_phone);

		update_user_meta($user_id, '_alum_home_phone', $home_phone);

		update_user_meta($user_id, '_alum_work_phone', $work_phone);



		update_user_meta($user_id, '_alum_are_you_hiring', $are_you_hiring);

		update_user_meta($user_id, '_alum_hiring_position', $hiring_position);

		update_user_meta($user_id, '_alum_seeking_employment', $seeking_employment);

		update_user_meta($user_id, '_alum_type_employment_seeking', $type_employment_seeking);





		if ($display_name != '') {

			$wpdb->update(

				'wp_users',

				array(

					'display_name' => $display_name,

				),

				array('ID' => $user_id),

				array(

					'%s',

				),

				array('%d')

			);
		}

		$log_user_out = false;

		if ($username != '') {

			//get current username and see if its changed

			$sql = "SELECT user_nicename FROM wp_users WHERE ID = '$user_id' LIMIT 1";

			$user_nicename = $wpdb->get_row($sql);

			if ($user_nicename->user_nicename != $username) {

				//see if its valid

				$username_avail = $this->checkUsername($username);

				if ($username_avail == true) {

					$log_user_out = true;

					$wpdb->update(

						'wp_users',

						array(

							'user_nicename' => $username,

						),

						array('ID' => $user_id),

						array(

							'%s',

						),

						array('%d')

					);
				} else {

					$results['resp'] = 'error';

					$results['mssg'] = 'The user name you entered is not available';
				}
			}
		}

		if ($email != '') {

			//get Current email (Login), if different, change and log user out...

			$sql = "SELECT user_email FROM wp_users WHERE ID = '$user_id' LIMIT 1";

			$user_email = $wpdb->get_row($sql);

			//echo "EMAIL: " . $user_email->user_email . "<BR>";

			if ($user_email->user_email != $email) {

				//see if its taken

				$sql = "SELECT user_email FROM wp_users WHERE user_email = '$email' LIMIT 1";

				$email_exist = $wpdb->get_row($sql);

				if (empty($email_exist)) {

					$wpdb->update(

						'wp_users',

						array(

							'user_email' => $email,

							'user_login' => $email,

						),

						array('ID' => $user_id),

						array(

							'%s',

							'%s',

						),

						array('%d')

					);

					//Log user out

					$log_user_out = true;
				} else {

					$results['resp'] = 'error';

					$results['mssg'] = 'The email address you entered is not available';
				}
			}
		}

		//print_r($_POST);

		//print_r($_FILES);

		//Ad ???

		if ($_FILES['ad'] != '') {

			$uploads_dir = alum_plugin_path . 'uploads/ads';

			$file = $_FILES['ad'];

			//$redir = $_SERVER['HTTP_REFERER'];

			//$alum_slug = $request['slug'];

			//$redir = get_bloginfo('url') . '/alum/' . $alum_slug;

			$image_x = 360;

			//$tmp_name = $_FILES['ad']['tmp_name'];

			//$name = basename($_FILES['ad']['name']);

			//move_uploaded_file($tmp_name, "$uploads_dir/$name");



			$group_id = $request['group_id'];

			$handle = new Upload($file);

			if ($handle->uploaded) {

				$handle->image_convert         = 'jpg';

				$handle->image_resize          = true;

				$handle->image_ratio_y         = true;

				$handle->image_x               = $image_x;

				$handle->jpeg_quality          = 90;

				$handle->Process($uploads_dir);

				$newname = $handle->file_dst_name;

				$_SESSION['alum_message'] = 'Your ad has been submitted';

				$_SESSION['alum_message_type'] = 'success';
			} else {

				$_SESSION['alum_message'] = 'Error (138): ' . $handle->error . '';

				$_SESSION['alum_message_type'] = 'error';
			}

			$handle->Clean();



			$wpdb->insert(

				'alum_ads',

				array(

					'group_id' => $group_id,

					'user_id' => $request['user_id'],

					'url' => '',

					'filename' => $newname

				),

				array(

					'%d',

					'%d',

					'%s',

					'%s'

				)

			);



			//$wpdb->show_errors();

			//$wpdb->print_error();



			$ad_id = $wpdb->insert_id;
		}



		//Email Somebody

		if ($user_id != '165') {

			$admin_email = get_option('admin_email');

			$subject = 'Someone has updated their profile on OurAlum App';

			$headers = "From: OurAlum<info@ouralum.com>\r\n";

			$headers .= "Reply-To: OurAlum<info@ouralum.com>\r\n";

			$headers .= "CC: mikejr@malouf.law\r\n";

			$headers .= "MIME-Version: 1.0\r\n";

			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$message = '<html><body>';

			$message .= '<h1>OurAlum.com</h1>';

			$message .= 'Hello Admin,<br><br>';

			$message .= $display_name . ' has updated their profile on OurAlum.com.<br><br>';

			$message .= '</body></html>';

			mail($admin_email, $subject, $message, $headers);
		} else {


			$log .= "\r\n";
			$log .= "Demo User is updated";

			$fp = fopen('api_log_alum.txt', 'w');
			fwrite($fp, $log);
			fclose($fp);
		}


		if (!isset($results)) {

			$results['resp'] = 'success';

			$results['mssg'] = 'Your user info was updated';
		}

		return $results;
	}



	public function buildProfileTimeline($user_id)
	{

		global $wpdb;

		//get dates

		$sql = "SELECT pledge_class, initiation_date, last_updated, college, fraternity FROM alumni WHERE user_id = '$user_id'";

		//echo $sql;

		$dates = $wpdb->get_row($sql);

		$timeline = '';

		//print_r($dates);

		//exit;

		if (!empty($dates)) {

			$timeline .= '<ul class="timeline timeline-centered">';

			foreach ($dates as $act => $date) {

				//echo "DATE: " . $date . "<BR>";

				$full_date = (strlen($date) == 4) ? $date . '-01-01' : $date;

				$date_str = strtotime(date("Y-m-d", strtotime($full_date)));

				$college = $dates->college;

				$fraternity = $dates->fraternity;

				switch ($act) {

					case 'pledge_class':

						$times[$date_str]['title'] = 'Attended ' . $college;

						$times[$date_str]['date'] = $date;

						break;

					case 'initiation_date':

						$times[$date_str]['title'] = 'Initiated ' . $fraternity;

						$times[$date_str]['date'] = $date;

						break;

					case 'last_updated':

						$times[$date_str]['title'] = 'Profile Updated';

						$times[$date_str]['date'] = $date;

						break;
				}
			}

			rsort($times);

			//echo '<pre>'; print_r($times); echo '</pre>'; 

			foreach ($times as $time) {

				$display_date = (strlen($time['date']) == 4) ? $time['date'] : date("M j, Y", strtotime($time['date']));

				if ($time['title'] != '') {

					$timeline .= '<li class="timeline-item">

				                    <div class="timeline-marker"></div>

				                    <div class="timeline-content">

				                        <h3 class="timeline-title">' . $time['title'] . '</h3>

				                        <p>' . $display_date . '</p>

				                    </div>

				                  </li>';
				}
			}

			$timeline .= '</ul>';
		}

		return $timeline;
	}



	public function getColleges()
	{

		global $wpdb;

		//$sql = "SELECT DISTINCT college FROM alumni";

		//$colleges = $wpdb->get_results($sql);

		sort($this->colleges);

		return $this->colleges;
	}



	public function getGroups()
	{

		global $wpdb;

		$sql = "SELECT * FROM groups";

		$groups = $wpdb->get_results($sql);

		return $groups;
	}



	public function getFraternitys()
	{

		global $wpdb;

		$sql = "SELECT DISTINCT fraternity FROM alumni";

		$frats = $wpdb->get_results($sql);

		return $frats;
	}



	public function getMemberCount()
	{

		global $wpdb;

		$sql = "SELECT COUNT(id) as member_count FROM alumni";

		$members = $wpdb->get_row($sql);

		return $members->member_count;
	}



	public function generateUniqueUsername($request)
	{

		global $wpdb;

		//Check Firstname Pledge Class

		$username = strtolower(str_replace(' ', '', $request['first_name']))  . $request['initiation_date'];

		$avail = $this->checkUsername($username);

		if ($avail == true) {

			return $username;
		}

		//Check first last pledge class

		$username = strtolower(str_replace(' ', '', $request['first_name']))  . strtolower(str_replace(' ', '', $request['last_name'])) . $request['initiation_date'];

		$avail = $this->checkUsername($username);

		if ($avail == true) {

			return $username;
		}

		//Fist name and incremental...

		$username = strtolower(str_replace(' ', '', $request['first_name']));

		$sql = "SELECT user_nicename FROM wp_users WHERE user_nicename LIKE '%$username%' ORDER BY user_nicename DESC LIMIT 1";

		$u = $wpdb->get_row($sql);

		//$username = $username . ($u['']);

		$username =  preg_replace("|(\d+)|e", "$1+1", $u['user_nicename']);

		return $username;
	}



	public function checkUsername($username)
	{

		global $wpdb;

		$sql = "SELECT user_nicename FROM wp_users WHERE user_nicename = '$username'";

		$exist = $wpdb->get_row($sql);

		if ($exist) {

			return false;
		}

		return true;
	}



	public function is_group_member($group_id)
	{

		global $wpdb;

		$user = wp_get_current_user();

		$user_id = $user->id;

		$sql = "SELECT g.id FROM alum_groups g, wp_users u, alumni a WHERE g.alum_id = a.id AND g.group_id = '$group_id' AND a.user_id = u.id AND u.id = '$user_id'";

		//echo "SQL: " . $sql;

		$exist = $wpdb->get_row($sql);

		if ($exist) {

			return true;
		}

		return false;
	}



	public function is_group_admin($group_id)
	{

		global $wpdb;

		$user = wp_get_current_user();

		$user_id = $user->id;

		$sql = "SELECT g.id FROM groups g, wp_users u WHERE g.id = '$group_id' AND g.admin_id = u.id AND u.id = '$user_id'";

		//echo "SQL: " . $sql;

		$exist = $wpdb->get_row($sql);

		if ($exist) {

			return true;
		}

		return false;
	}



	public function showSideCalendar($month = null, $year = null,  $show_events = false, $group_id = '')
	{

		global $wpdb;

		$calendar = '';

		//echo "M:$month - Y:$year\r\n";

		if ($month == null || $year == null) {

			$month = date('m');

			$year = date('Y');
		}

		$date = mktime(12, 0, 0, $month, 1, $year);

		$daysInMonth = date("t", $date);

		$offset = date("w", $date);

		$rows = 1;

		$prev_month = $month - 1;

		$prev_year = $year;

		if ($month == 1) {

			$prev_month = 12;

			$prev_year = $year - 1;
		}

		$today_date = date("Y-m-d");

		$next_month = $month + 1;

		$next_year = $year;

		if ($month == 12) {

			$next_month = 1;

			$next_year = $year + 1;
		}

		$calendar .= "<div class='panel-heading text-center'><div class='row'><div class='col-md-3 col-xs-4 left-nav'><a class='";

		if ($show_events != true) {

			$calendar .= "ajax-navigation ";
		}

		$calendar .= "btn btn-default btn-sm' href='?mo=" . $prev_month . "&yr=" . $prev_year . "'><span class='glyphicon glyphicon-arrow-left'></span></a></div><div class='col-md-6 col-xs-4'><strong>" . date("F Y", $date) . "</strong></div>";

		$calendar .= "<div class='col-md-3 col-xs-4  right-nav'><a class='";

		if ($show_events != true) {

			$calendar .= "ajax-navigation ";
		}

		$calendar .= "btn btn-default btn-sm' href='?mo=" . $next_month . "&yr=" . $next_year . "'><span class='glyphicon glyphicon-arrow-right'></span></a></div></div></div>";

		$calendar .= "<table class='table table-bordered'>";

		$calendar .= "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";

		$calendar .= "<tr>";

		for ($i = 1; $i <= $offset; $i++) {

			$calendar .= "<td></td>";
		}

		for ($day = 1; $day <= $daysInMonth; $day++) {

			$tday = $day;

			if ($day < 9) {

				$tday = '0' . $day;
			}

			$date = $year . "-" . $month . "-" . $tday;

			//see if any events for this day...

			$sql = "SELECT user_id, event_title, event_date FROM alum_events WHERE group_id = '$group_id' AND DATE(event_date) = '$date'";

			//echo "$sql";

			$events = $wpdb->get_results($sql);



			if (($day + $offset - 1) % 7 == 0 && $day != 1) {

				$calendar .= "</tr><tr>";

				$rows++;
			}

			$calendar .= '<td class="';

			if ($date == $today_date) {

				$calendar .= 'today-cell';
			}

			if (!empty($events) && $show_events != true) {

				$calendar .= ' event-cell';
			}

			$calendar .= '" style="width: -moz-min-content;"><span class="add-event-cell" data-date="' . date("m/d/Y", strtotime($date)) . '"><i class="fa fa-plus"></i></span><span class="day-cell">' . $day . '</span>';

			if ($show_events == true && !empty($events)) {

				foreach ($events as $event) {

					$calendar .= '<div class="event-cell"><span class="event-title-cell">' . $event->event_title . '</span> - <span class="event-time-cell">' . date("g:i A", strtotime($event->event_date)) . '</span></div>';
				}
			}

			$calendar .= '</td>';
		}

		while (($day + $offset) <= $rows * 7) {

			$calendar .= "<td></td>";

			$day++;
		}

		$calendar .= "</tr>";

		$calendar .= "</table>";

		return $calendar;
	}



	public function sendAlumMail($params)
	{

		global $wpdb;

		date_default_timezone_set('America/Chicago');

		if ($params['reply_to'] == '') {

			$params['reply_to'] = 'mikejr@malouf.law ';
		}

		if ($params['from_name'] == '') {

			$params['from_name'] = 'OurAlum.com';
		}



		if ($params['from_email'] == '') {

			$params['from_email'] = 'info@ouralum.com';
		}

		$message = $params['message'];

		//print_r($params);

		//exit;		

		//Format message....

		$html = '<table width="100%" cellpadding="0" cellspacing="0"><tr><td align="center"><table width="700" cellpadding="0" cellspacing="0"><tr><td height="140" bgcolor="#FFFFFF">';

		$html .= '<img src="' . get_template_directory_uri() . '/lib/images/email_logo.jpg" alt="OurAlum">';



		$html .= '</td></tr><tr><td>';

		$html .= $params['message'];

		$html .= '<br><br>Thanks,<br>The OurAlum Team';

		$html .= '</td></tr><tr><td align="center"><br><br>&copy; 2017 - ' . date("Y") . ' OurAlum.comreg;<br><br><font size="-2">This electronic message is confidential and is intended only for the use of the individual to whom it is addressed. The information may also be legally privileged. This transmission is sent in trust, for the sole purpose of delivery to the intended recipient. If you have received this transmission in error, you are hereby notified that any use, dissemination, distribution or reproduction of this transmission is strictly prohibited. If you are not the intended recipient, please immediately notify the sender and delete the message from your system.</font>';

		$html .= '</td></tr></table></td></tr></table>';



		$headers = 'From: ' . $params['from_name'] . ' <' . $params['from_email'] . '>' . "\r\n";

		$headers .= 'Content-type: text/html' . "\r\n";

		require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/alumn/lib/php/phpmailer/PHPMailerAutoload.php');

		$mail = new PHPMailer(true);

		try {

			$mail->SMTPDebug  = 0;

			/* Setting to other email causes issues on some emails (Yahoo) */

			$mail->SetFrom($params['from_email'], $params['from_name']);

			$mail->AddReplyTo($params['reply_to']);

			$message = stripslashes($message);

			$mail->Subject = $params['subject'];

			$mail->AltBody = $message;

			$mail->MsgHTML($html);

			$mail->AddAddress($params['to']);

			$mail->AddBCC('judsonc75@gmail.com', 'Judson Cooper');

			if ($params['attachment']) {

				//$mail->AddAttachment($attachments);

				$mail->AddAttachment($params['attachment']['file_name'], $params['attachment']['name']);
			}

			if (!$mail->Send()) {

				$mail_send['msg'] = "Mailer Error 11a: " . $mail->ErrorInfo;

				$mail_send['resp'] = false;
			} else {

				$mail_send['msg'] = "Message sent!";

				$mail_send['resp'] = true;
			}

			$mail->ClearAddresses();
		} catch (phpmailerException $e) {

			$mail_send['msg'] =  "Error 101: " . $e->errorMessage(); //Pretty error messages from PHPMailer

			$mail_send['resp'] = false;
		} catch (Exception $e) {

			$mail_send['msg'] =  "Error 102: " .  $e->getMessage(); //Boring error messages from anything else!

			$mail_send['resp'] = false;
		}

		return $mail_send;
	}



	public function sendAlumPushNotifications($params)
	{







		//$user_id, $title, $message

		global $wpdb;

		$sql = "SELECT app_token FROM alumni WHERE user_id = '" . $params['user_id'] . "'";

		//echo "SQL: $sql";

		$result = $wpdb->get_row($sql);

		$token = $result->app_token;



		/*

		define( 'API_ACCESS_KEY', 'AAAA7a75rDo:APA91bEUL8IVtj1LlZzCuxffeK8J3BcrOyAJEdtbMz2VDxELd_O_6mRU9Lwyz9NiMag14hCCMb0BQUJtg8EOLXGn6VjC-Sb8p7lW1pTjSyitrZvBdxWt_VE8IREIUB0GL7V6NavZMsXy'); 

		//Get device ID

		$sql = "SELECT app_token FROM alumni WHERE user_id = '" . $params['user_id'] . "'";

//echo "SQL: $sql";

		$result = $wpdb->get_row($sql);	

		$token = $result->app_token;



		$log = "**********************\r\n";

		$log .= "" . date("Y-m-d g:i a") . "\r\n";

		$log .= "TOKEN: " . print_r($token, true) . "\r\n";

		//build message

		$data = array(

			'message'       => $params['message'],

			'title'         => $params['title'],

			'subtitle'      => '',

			'tickerText'    => '',

			'vibrate'   	=> 1,

			'sound'     	=> 1

		);



		$registration_id = array($token);



		$fields = array(

			'registration_ids'  => $registration_id,

			'notification' => array (

					"body" => $data['message'],

					"title" => $data['title'],

					"icon" => "myicon"

			)

		);



		$headers = array(

			'Authorization: key=' . API_ACCESS_KEY,

			'Content-Type: application/json'

		);



		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

		curl_setopt( $ch, CURLOPT_POST, true );

		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );

		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );

		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );		

		$result = curl_exec($ch );

		if(curl_errno($ch)){

			$log .= 'Request Error:' . curl_error($ch) . "\r\n";

		}

	

		if($result === FALSE) {

			$log .= 'Request Error:' . curl_error($ch) . "\r\n";

			//die("Curl failed: " . curl_error($ch));

		}

		curl_close( $ch );

		$response = json_decode($result, true);

		$log .= 'FCM RESP:' . print_r($response, true) . "\r\n";



		if($response['results'][0]['error'] != '') {

			if($response['results'][0]['error'] == 'NotRegistered') {

				//unregister the ID

				$sql = "UPDATE users SET app_token = '' WHERE id = %s";

				DB::query( $sql , $user_id );

				$log .= "REG ID: $registration_id Deleted\r\n";

			}

		}

		*/





		$content = array(

			"en" => 'English Message'

		);



		$fields = array(

			'app_id' => "1146d78d-655a-4901-a1fb-f65f626b624b",

			'include_player_ids' => array($token),

			/*'data' => array("foo" => "bar"),*/

			'contents' => array('en' => $params['message'])

		);



		$fields = json_encode($fields);

		print("\nJSON sent:\n");

		print($fields);



		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);



		$response = curl_exec($ch);

		curl_close($ch);



		return $response;





		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/temp/send_push.txt', 'w');

		fwrite($fp, $log);

		fclose($fp);
	}





	public function get_alum_percents()
	{

		global $wpdb;

		//if($_REQUEST['year'] == '' && $_REQUEST['init_year'] != '') {

		//	$_REQUEST['year'] = $_REQUEST['init_year'];

		//}

		$init_year = $_REQUEST['init_year'];

		$year = $_REQUEST['year'];

		$type = $_REQUEST['type'];

		if ($_REQUEST['group_id'] == '' && $_REQUEST['id'] != '') {

			$_REQUEST['group_id'] = $_REQUEST['id'];
		}

		$group_id = $_REQUEST['group_id'];

		//print_r($_REQUEST);

		//Get members count...

		$sql = "SELECT COUNT(a.id) AS members_count FROM alum_groups g, alumni a LEFT JOIN wp_users u ON (a.user_id = u.ID) WHERE g.group_id = '" . $group_id . "' AND a.id = g.alum_id AND g.status = 1";

		$sql .= " AND a.id != 2203";

		if ($year != '' && $year != 'all') {

			$sql .= " AND YEAR(STR_TO_DATE(initiation_date, \"%Y\")) = '$year'";
		} elseif ($init_year != 'all') {

			$sql .= " AND YEAR(STR_TO_DATE(initiation_date, \"%Y\")) = '$init_year'";
		}

		//echo "SQL: $sql";

		$members_count = $wpdb->get_row($sql);

		//print_r($members_count);

		$sql = "SELECT COUNT(a.id) AS active_count FROM alum_groups g, alumni a LEFT JOIN wp_users u ON (a.user_id = u.ID) WHERE g.group_id = '" . $group_id . "' AND a.id = g.alum_id AND g.status = 1";

		$sql .= " AND a.id != 2203 AND a.user_id != ''";

		if ($year != '' && $year != 'all') {

			$sql .= " AND YEAR(STR_TO_DATE(initiation_date, \"%Y\")) = '$year'";
		} elseif ($init_year != 'all') {

			$sql .= " AND YEAR(STR_TO_DATE(initiation_date, \"%Y\")) = '$init_year'";
		}

		//echo $sql . "<BR>";

		$active_count = $wpdb->get_row($sql);

		//print_r($active_count);

		$percent = round(($active_count->active_count / $members_count->members_count) * 100, 2);

		$non_members_count = $members_count->members_count - $active_count->active_count;



		//List other years percentage

		//Get members count by year...

		$sql = "SELECT COUNT(a.id) AS members_count, YEAR(initiation_date) AS year FROM alum_groups g, alumni a LEFT JOIN wp_users u ON (a.user_id = u.ID) WHERE g.group_id = '" . $group_id . "' AND a.id = g.alum_id AND g.status = 1";

		$sql .= " AND a.id != 2203";

		if ($year != 'all' && $year != '') {

			$sql .= " AND YEAR(STR_TO_DATE(initiation_date, \"%Y\")) = '$year'";
		}

		$sql .= " GROUP BY YEAR(a.initiation_date)";

		$members_count_by_year = $wpdb->get_results($sql);

		//Active By Year

		$sql = "SELECT COUNT(a.id) AS active_count, YEAR(initiation_date) AS year FROM alum_groups g, alumni a LEFT JOIN wp_users u ON (a.user_id = u.ID) WHERE g.group_id = '" . $group_id . "' AND a.id = g.alum_id AND g.status = 1";

		$sql .= " AND a.id != 2203 AND a.user_id != ''";

		if ($year != 'all' && $year != '') {

			$sql .= " AND YEAR(STR_TO_DATE(initiation_date, \"%Y\")) = '$year'";
		}

		$sql .= " GROUP BY YEAR(a.initiation_date)";

		$active_count_by_year = $wpdb->get_results($sql);



		//print_r($members_count_by_year);

		//print_r($active_count_by_year);

		foreach ($members_count_by_year as $members_by_year) {

			$year = $members_by_year->year;

			$count = $members_by_year->members_count;

			$yp[$year]['count'] = $count;
		}

		foreach ($active_count_by_year as $active_by_year) {

			$year = $active_by_year->year;

			$count = $active_by_year->active_count;

			$yearly_percent[$year]['percent'] = ceil(($count / $yp[$year]['count']) * 100);
		}

		//print_r($yearly_percent);

		if ($type == 'top-classes' || $type == '') {

			arsort($yearly_percent);
		} elseif ($type == 'bottom-classes') {

			asort($yearly_percent);
		}

		//Make the year list..

		$cc = 0;

		$list_html = '';

		foreach ($yearly_percent as $year => $percentage) {

			$list_html .= '<li><span class="tp-c">Class of ' . $year . '</span><span class="tp-p">' . $percentage['percent'] . '%</span></li>';

			$cc++;

			if ($cc == 3) {

				break;
			}
		}



		$script = '<script id="alum_chart">

					(function($) {

					  	$("#doughnutChart").drawDoughnutChart([

					    	{ title: "Members", value :  ' . $active_count->active_count .  ',  color: "#337ab7" },

					    	{ title: "Non-Members", value: ' . $non_members_count . ',   color: "#e1e1e1" }

					  	]);

				  	})(jQuery);

				  	</script>';

		$perc = round(($active_count->active_count / ($non_members_count + $active_count->active_count)) * 100);

		$results['list_html'] = $list_html;

		$results['script'] = $script;

		$results['percent'] = $perc;

		$results['resp'] = 'success';

		return $results;

		//exit;



	}
}
