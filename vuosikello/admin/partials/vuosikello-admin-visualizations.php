<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       utu.fi
 * @since      1.0.0
 *
 * @package    Vuosikello
 * @subpackage Vuosikello/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<style>

path {
  fill: #ccc;
  stroke: #333;
  stroke-width: 1.5px;
  transition: fill 250ms linear;
  transition-delay: 150ms;
}

path:hover {
  fill: #999;
  stroke: #000;
  transition-delay: 0;
}

text {
  font: 15px sans-serif;
  text-anchor: middle;
  /* text-shadow: 0 1px 0 #fff, 0 -1px 0 #fff, 1px 0 0 #fff, -1px 0 0 #fff; */
}

#event-info {
  font-size: 1.5em;
  width: 400px;
}

#event-info span {
  font-weight: bold;
  padding: 10px 10px 10px 10px;
}

#event-info div {
  padding: 10px 10px 10px 10px;
}

.visualization-container {
  display: flex;
  flex-direction: row;
}

</style>
<div class="wrap">
  <div class="visualization-container">
    <form id="category-select" class="category-select" action="<?php echo esc_url(admin_url('admin.php?')); ?>" method="get">
      <h2><?php _e( 'Kategoria 1', 'vuosikello' ); ?></h2>
      <?php wp_dropdown_categories( array(
        'taxonomy' => 'vuosikello_event_category',
        'name' => 'category1',
        'show_option_all' => 'All',
        'selected' => (isset($_GET['category1']) ? $_GET['category1'] : 0)
      ) ); ?>
      <button type="button" onclick="jQuery('.cat1text').toggle();">Toggle text</button>
      <h2><?php _e( 'Kategoria 2' ); ?></h2>
      <?php wp_dropdown_categories( array(
        'taxonomy' => 'vuosikello_event_category',
        'name' => 'category2',
        'show_option_all' => 'All',
        'selected' => (isset($_GET['category2']) ? $_GET['category2'] : 0)
      ) ); ?>
      <button type="button" onclick="jQuery('.cat2text').toggle();"><?php __("Toggle text", "vuosikello"); ?></button>
      <input type="hidden" name="page" value="vuosikello-visualizations">
      <input type="submit" name="submit" value="<?php __("Refresh", "vuosikello"); ?>" />
    </form>
    <div id="event-info">
      <span id="event-name"><?php __("Event name:", "vuosikello"); ?></span><div id="event-name-content"></div>
      <span id="event-description"><?php __("Event description:", "vuosikello"); ?></span><div id="event-description-content"></div>
      <span id="event-start"><?php __("Starts:", "vuosikello"); ?></span><div id="event-start-content"></div>
      <span id="event-end"><?php __("Ends:", "vuosikello"); ?></span><div id="event-end-content"></div>
    </div>
    <svg width="960" height="960" font-family="sans-serif" font-size="10" text-anchor="middle"></svg>
  </div>
</div>

<script>

<?php

$category_1_args = array(
	'posts_per_page'   => 100,
	'offset'           => 0,
	'category'         => '',
	'category_name'    => '',
	'orderby'          => 'date',
	'order'            => 'DESC',
	'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',
	'post_type'        => 'vuosikello_event',
	'post_mime_type'   => '',
	'post_parent'      => '',
	'author'	   => '',
	'author_name'	   => '',
	'post_status'      => 'publish',
	'suppress_filters' => true
);

if(isset($_GET['category1'])) {
  if($_GET['category1'] != 0) {
    $category1 = array(
        array(
            'taxonomy' => 'vuosikello_event_category',
            'terms' => $_GET['category1'],
            'field' => 'term_id',
        )
    );
    $category_1_args['tax_query'] = $category1;
  }
}

$category_2_args = array(
	'posts_per_page'   => 1000,
	'offset'           => 0,
	'category'         => '',
	'category_name'    => '',
	'orderby'          => 'date',
	'order'            => 'DESC',
	'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',
	'post_type'        => 'vuosikello_event',
	'post_mime_type'   => '',
	'post_parent'      => '',
	'author'	   => '',
	'author_name'	   => '',
	'post_status'      => 'publish',
	'suppress_filters' => true
);

if(isset($_GET['category2'])) {
  if($_GET['category2'] != 0) {
    $category2 = array(
        array(
            'taxonomy' => 'vuosikello_event_category',
            'terms' => $_GET['category2'],
            'field' => 'term_id',
        )
    );
    $category_2_args['tax_query'] = $category2;
  }
}

if(isset($_GET['post-parent'])) {
  if($_GET['post-parent'] != 0) {
    $category_1_args['post_parent'] = $_GET['post-parent'];
    $category_2_args['post_parent'] = $_GET['post-parent'];
  }
}

// Check if groups plugin is enabled and get info about the user
if(is_plugin_active("groups/groups.php")) {
  $groups_user = new Groups_User( get_current_user_id() );
  // get group objects
  $user_groups = $groups_user->groups;
  // get group ids (user is direct member)
  $user_group_ids = $groups_user->group_ids;
  // get group ids (user is direct member or by group inheritance)
  $user_group_ids_deep = $groups_user->group_ids_deep;

  $category_1_args['meta_query'] = array(
    array(
      'key' => 'groups-read',
      'value' => $user_group_ids_deep,
      'compare' => 'IN'
    )
  );

  $category_2_args['meta_query'] = array(
    array(
      'key' => 'groups-read',
      'value' => $user_group_ids_deep,
      'compare' => 'IN'
    )
  );
}
else {
  exit("Groups plugin not enabled!");
}

// Posts json generation
$category1posts = get_posts( $category_1_args );
foreach($category1posts as $key => $value){

  $metaData = get_post_meta($value->ID,'', true);
  // Let's calculate start and endAngles
  // Angles are in radians
  $angles = calculateStartEndAngles($metaData["vk_events_startdate"][0], $metaData["vk_events_enddate"][0]);

  $value->startAngle = $angles['startAngle'];
  $value->endAngle = $angles['endAngle'];
  $value->vk_events_startdate = $metaData["vk_events_startdate"][0];
  $value->vk_events_enddate = $metaData["vk_events_enddate"][0];
  $value->start_date = date("j.n.Y", $metaData["vk_events_startdate"][0]) . " (" . date("W", $metaData["vk_events_startdate"][0]) . ")";
  $value->end_date = date("j.n.Y", $metaData["vk_events_enddate"][0]) . " (" . date("W", $metaData["vk_events_enddate"][0]) . ")";
}
echo "var jsonCategory1 = " . wp_json_encode($category1posts) . ";";

// Posts json generation
$category2posts = get_posts( $category_2_args );
foreach($category2posts as $key => $value){

  $metaData = get_post_meta($value->ID,'', true);
  // Let's calculate start and endAngles
  // Angles are in radians
  $angles = calculateStartEndAngles($metaData["vk_events_startdate"][0], $metaData["vk_events_enddate"][0]);

  $value->startAngle = $angles['startAngle'];
  $value->endAngle = $angles['endAngle'];
  $value->vk_events_startdate = $metaData["vk_events_startdate"][0];
  $value->vk_events_enddate = $metaData["vk_events_enddate"][0];
  $value->start_date = date("j.n.Y", $metaData["vk_events_startdate"][0]) . " (" . date("W", $metaData["vk_events_startdate"][0]) . ")";
  $value->end_date = date("j.n.Y", $metaData["vk_events_enddate"][0]) . " (" . date("W", $metaData["vk_events_enddate"][0]) . ")";
}

echo "var jsonCategory2 = " . wp_json_encode($category2posts) . ";";

// Months json generation
$monthAngles = generateMonths(date("Y"));
echo "var monthsJson = " . wp_json_encode($monthAngles) . ";";

// Weeks json generation
$weekAngles = generateWeeks(date("Y"));
echo "var weeksJson = " . wp_json_encode($weekAngles) . ";";

/***
 * Helper functions
 */


/***
 * Calculates angles for circle visualization
 */
function calculateStartEndAngles($startDate, $endDate) {
  $daysInYear = (idate('L', $startDate) == 1 ? 366 : 365);
  $degreeMultiplier = 2*pi() / $daysInYear;

  $startAngle = idate('z', $startDate) * $degreeMultiplier;
  $endAngle = idate('z', ($endDate)+1) * $degreeMultiplier;

  $result = array( "startAngle" => $startAngle, "endAngle" => $endAngle, "month" => strftime("%B", $startDate), "week" => idate("W", $startDate) );
  return $result;
}

/***
 * Generates start and end angles of months on given year
 */
function generateMonths($year) {
  $months = array();
  // Leap year check
  $isLeapYear = idate('L', strtotime("1 January " . $year)); // 1 if leap year, 0 otherwise
  $februaryLastDay = 28;
  if($isLeapYear)
    $februaryLastDay = 29;

  // Not beautiful but at least it works...
  $january = calculateStartEndAngles(strtotime("1 January " . $year), strtotime("31 January " . $year));
  $february = calculateStartEndAngles(strtotime("1 February " . $year), strtotime($februaryLastDay . " February " . $year));
  $march = calculateStartEndAngles(strtotime("1 March " . $year), strtotime("31 March " . $year));
  $april = calculateStartEndAngles(strtotime("1 April " . $year), strtotime("30 April " . $year));
  $may = calculateStartEndAngles(strtotime("1 May " . $year), strtotime("31 May " . $year));
  $june = calculateStartEndAngles(strtotime("1 June " . $year), strtotime("30 June " . $year));
  $july = calculateStartEndAngles(strtotime("1 July " . $year), strtotime("31 July " . $year));
  $august = calculateStartEndAngles(strtotime("1 August " . $year), strtotime("31 August " . $year));
  $september = calculateStartEndAngles(strtotime("1 September " . $year), strtotime("30 September " . $year));
  $october = calculateStartEndAngles(strtotime("1 October " . $year), strtotime("31 October " . $year));
  $november = calculateStartEndAngles(strtotime("1 November " . $year), strtotime("30 November " . $year));
  $december = calculateStartEndAngles(strtotime("1 December " . $year), strtotime("31 December " . $year));

  array_push($months, $january, $february, $march, $april, $may, $june, $july, $august, $september, $october, $november, $december);
  return $months;
}

/***
 * Generates start and end angles of weeks on given year
 */
function generateWeeks($year) {
  $weeks = array();

  $firstMonday = findFirstMonday(date("Y"));

  // add last week of previous year
  $lastWeek = calculateStartEndAngles(strtotime("-1 week", $firstMonday), $firstMonday);
  $lastWeek["startAngle"] = 0;
  array_push($weeks, $lastWeek);

  $date = $firstMonday;

    while(date("Y", $date) == $year) {
      $week = calculateStartEndAngles($date, strtotime("+1 week", $date));
      $date = strtotime("+1 week", $date);
      array_push($weeks, $week);
    }

    // get last item and change endAngle to 2*pi() so that it doesn't overlap with last years last week

    $weeks[count($weeks) -1]["endAngle"] = 2*pi();

  return $weeks;
}

/***
 * Finds first monday of first week
 */
function findFirstMonday($year) {
  $firstMonday = 0;
  for($i = 0; $i < 7; $i++) {
      if(idate("W", strtotime($i + 1 . " January " . $year)) == 1) {
        $firstMonday = $i + 1;
        return strtotime($firstMonday . " January " . $year);
      }
   }
}

// Not in use
function divideOverlappingEvents($events) {

  if(count($events) === 0) {
    return null;
  }

  usort($events, function($a, $b) {
    if($a->vk_events_startdate == $b->vk_events_startdate) {
      return 0;
    }
    return ($a->vk_events_startdate < $b->vk_events_startdate) ? -1 : 1;
  });

  $overlappingEvents = array();
  $okEvents = array();
  foreach($events as $e) {
    foreach($events as $comparable) {
      if($e->ID !== $comparable->ID) {
        if(!in_array($e, $overlappingEvents) && !in_array($e, $okEvents)) {
          array_push($okEvents, $e);
          if($e->vk_events_startdate <= $comparable->vk_events_enddate && $e->vk_events_enddate >= $comparable->vk_events_startdate) {
            array_push($overlappingEvents, $comparable);
          }
        }
      }
    }
  }

  return array($okEvents, divideOverlappingEvents($overlappingEvents));
}

?>

// todo: funktiot kehien generointiin. Eli funktio joka saa argumentteina: data, outerRadius, innerRadius, tyylejä, hoverFunktio?
// D3
var svg = d3.select("svg"),
    width = +svg.attr("width"),
    height = +svg.attr("height");

var outerRadius = height / 2 - 80,
    innerRadius = outerRadius / 2,
    cornerRadius = 10;

var arc = d3.arc()
    .padRadius(outerRadius)

var svg = d3.select("svg")
    .attr("width", width)
    .attr("height", height)
  .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

// Background
var background = svg
  .append("circle")
  .attr("cx", 0)
  .attr("cy", 0)
  .attr("r", outerRadius + 80)
  .style("fill", "#dbfbff");

// Months
var monthNodes = svg.selectAll("months")
  .data(monthsJson)
  .enter().append("g")
  .attr("class", "monthNode");

monthNodes.append("path")
    .each(function(d) { d.outerRadius = outerRadius + 75; d.innerRadius = innerRadius + 250 })
    .attr("d", arc)
    .attr("class", "monthArc")
	  .attr("id", function(d,i) { return "monthArc_"+i; })
    .style("fill", "#66ab8c");

monthNodes.append("text")
    .attr("class", "monthText")
    .attr("x", 45)
    .attr("dy", 18)
  .append("textPath")
    .attr("xlink:href", function(d,i) {return "#monthArc_"+i;})
    .text(function(d) { return d.month});

// Weeks
var weekNodes = svg.selectAll("weeks")
    .data(weeksJson)
    .enter().append("g")
    .attr("class", "weekNode")

weekNodes.append("path")
    .each(function(d) { d.innerRadius = outerRadius; d.outerRadius = outerRadius + 40 })
    .attr("d", arc)
    .style("fill", "#fff7c0")

weekNodes.append("text")
    .attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
    .attr("dy", ".35em")
    .text(function(d) { return d.week});

// Events 1
var category1Events = svg.selectAll("events1")
    .data(jsonCategory1)
    .enter().append("g")
    .attr("class", "events1node")

category1Events.append("path")
    .each(function(d) { d.innerRadius = innerRadius + 60; d.outerRadius = outerRadius - 10 })
    .attr("d", arc)
    .style("fill", "#6ec4db")
    .style("cursor", "pointer")
    .on("mouseover", function(d) { showInfo(d); })
    .on("mouseout", function(d) { emptyInfo(); })
    .on("click", function(d) { window.location.href = "<?php echo admin_url("post.php"); ?>?post=" + d.ID + "&action=edit" });

category1Events.append("text")
    .attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
    .attr("dy", ".35em")
    .attr("class", "cat1text")
    .text(function(d) { return d.post_title});

// Events 2
var category2Events = svg.selectAll("events2")
    .data(jsonCategory2)
    .enter().append("g")
    .attr("class", "events2node")

category2Events.append("path")
    .each(function(d) { d.innerRadius = innerRadius - 60; d.outerRadius = outerRadius - 150 })
    .attr("d", arc)
    .style("fill", "#fa7c92")
    .style("cursor", "pointer")
    .on("mouseover", function(d) { showInfo(d); })
    .on("mouseout", function(d) { emptyInfo(); })
    .on("click", function(d) { window.location.href = "<?php echo admin_url("post.php"); ?>?post=" + d.ID + "&action=edit" });

category2Events.append("text")
    .attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
    .attr("dy", ".35em")
    .attr("class", "cat2text")
    .text(function(d) { return d.post_title});

var degreeMultiplier = 2*Math.PI / daysInYear();
var angle = dayOfYear() * degreeMultiplier;

// Viisari
var pointer = svg.append("line")
  .attr("x1", 0)
  .attr("y1", 0)
  .attr("x2", Math.cos(angle-Math.PI*.5)*500)
  .attr("y2", Math.sin(angle-Math.PI*.5)*500)
  .attr("stroke-width", 3)
  .attr("stroke", "black");

function daysInYear() {
  var dateNow = new Date();
  var isLeap = new Date(dateNow.getFullYear(), 1, 29).getMonth() == 1;
  var days = isLeap == 1 ? 366 : 365;
  return days;
}

function dayOfYear() {
  var now = new Date();
  var start = new Date(now.getFullYear(), 0, 0);
  var diff = now - start;
  var oneDay = 1000 * 60 * 60 * 24;
  var day = Math.floor(diff / oneDay);
  return day;
}

function arcTween(outerRadius, delay) {
  return function() {
    d3.select(this).transition().delay(delay).attrTween("d", function(d) {
      var i = d3.interpolate(d.outerRadius, outerRadius);
      return function(t) { d.outerRadius = i(t); return arc(d); };
    });
  };
}

/***
 * Experimental function for highlighting events
 */
function highlightAndShowText(element, delay) {
  d3.select(element).transition().styleTween("fill", function() {
    return function(t) {
      return "hsl(" + t * 180 + ",100%,50%)";
    };
  });
}

/***
 * Shows event info in page
 */
function showInfo(data) {
  jQuery("#event-name-content").html(data.post_title);
  jQuery("#event-description-content").html(data.post_content);
  jQuery("#event-start-content").html(data.start_date);
  jQuery("#event-end-content").html(data.end_date);
}

/***
 * Hides the info
 */
function emptyInfo() {
  jQuery("#event-name-content").html("");
  jQuery("#event-description-content").html("");
  jQuery("#event-start-content").html("");
  jQuery("#event-end-content").html("");
}

</script>
