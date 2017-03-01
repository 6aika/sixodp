var datavis2 = {}


// PROPS

datavis2.element = d3.select('.js-datavis-2')
datavis2.inputs = {
  startDate: datavis2.element.select('.js-datavis-start-date'),
  endDate: datavis2.element.select('.js-datavis-end-date'),
  downloadButton: datavis2.element.select('.js-datavis-download-button'),
}
datavis2.filters = {
  startDate: undefined,
  endDate: undefined,
  organization: undefined,
}
datavis2.data = []



// INIT

datavis2.init = function (data) {
  self = this
  self.eventListeners()
  self.curate(data)
  self.render()
}



// PREPROCESS DATA

datavis2.curate = function (data) {
  var self = this
  tmpData = {}

  // What's the earliest date of releasing data
  startDateString = undefined
  data.forEach(function(dataset) {
    if (dataset.private) {
      return true
    }
    if (typeof startDateString === 'undefined' || dataset['date_released'] < startDateString) {
      startDateString = dataset['date_released']
    }
  })
  startDate = moment.utc(startDateString).subtract(1, 'days').toDate()

  // Last date on graph is today
  endDate = moment.utc()
    .hour(0)
    .minute(0)
    .second(0)
    .millisecond(0)
    .toDate()

  // First, create an empty datatable with the correct datespan
  dateToAdd = startDate
  while (dateToAdd <= endDate) {
    dateString = moment(dateToAdd).format('YYYY-MM-DD')
    tmpData[dateString] = {
      date: dateToAdd,
      added: [],
      removed: [],
      availableCount: undefined,
    }
    dateToAdd = moment.utc(dateToAdd).add(1, 'days').toDate()
  }

  // Add each dataset to its creation date
  data.forEach(function(dataset) {
    // Skip private datasets completely
    if (dataset.private || dataset.date_released > moment(endDate).format('YYYY-MM-DD')) {
      return true
    }

    // Add creation of this dataset
    tmpData[dataset.date_released].added.push(dataset.title_translated[lang])

    // Add removing of this dataset if that has happened
    // if (dataset.state == 'deleted' && dataset.date_modified >= startDateString) { // not 'active'
    //   tmpData[dataset.date_modified].removed.push(dataset.title_translated[lang])
    // }
  })

  // TODO: Get data from removed datasets


  // Calculate cumulative values
  dateToCumulate = startDate
  availableCount = 0
  while (dateToCumulate <= endDate) {
    dateString = moment(dateToCumulate).format('YYYY-MM-DD')
    availableCount = availableCount + tmpData[dateString].added.length - tmpData[dateString].removed.length
    tmpData[dateString].availableCount = availableCount
    dateToCumulate = dateToAdd = moment.utc(dateToCumulate).add(1, 'days').toDate()
  }

  // Turn into array
  self.data = []
  for (i in tmpData) {
    self.data.push(tmpData[i])
  }
  console.log('Data 2 storage:', self.data)
}


// USER INTERACTIONS

datavis2.eventListeners = function () {
  self.inputs.startDate.on('keyup', function () {
    date = validDate(self.inputs.startDate.property('value'))
    if (!date) {
      return false
    }
    console.log('New valid start date', date)
  })

  self.inputs.endDate.on('keyup', function () {
    date = validDate(self.inputs.endDate.property('value'))
    if (!date) {
      return false
    }
    console.log('New valid end date', date)
  })

  self.inputs.downloadButton.on('click', function () {
    console.log('clicked download')
  })
}



// RENDER THE DATA VISUALIZATION

datavis2.render = function () {

  var svg = d3.select('.js-datavis-2 .datavis-svg')
  var margin = {top: 20, right: 20, bottom: 30, left: 50}
  var width = 800 // +svg.attr("width") - margin.left - margin.right,
  var height = 300 // +svg.attr("height") - margin.top - margin.bottom,
  var g = svg.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")")

  // var parseTime = d3.timeParse("%y-%m-%d");
  xExtent = d3.extent(this.data, function(d) { return d.date })
  var x = d3.scaleTime()
    .domain(xExtent)
    .rangeRound([0, width])

  var y = d3.scaleLinear()
    .rangeRound([height, 0])
    .domain(d3.extent(this.data, function(d) { return d.availableCount }))

  // var zoom = d3.zoom()
  //   .scaleExtent([1 / 2, 2])
  //   .translateExtent([[-width, -Infinity], [2 * width, Infinity]])
  //   .on("zoom", onZoom1);

  // var zoomRect = svg.append("rect")
  //   .attr("width", width)
  //   .attr("height", height)
  //   .attr("fill", "none")
  //   .attr("pointer-events", "all")
  //   .call(zoom);

  g.append("clipPath")
      .attr("id", "clip")
    .append("rect")
      .attr("width", width)
      .attr("height", height);

  // zoom.translateExtent([
  //   [x(xExtent[0]), -Infinity],
  //   [x(xExtent[1]), Infinity]
  // ])

  y.domain([0, d3.max(this.data, function(d) { return d.availableCount })])
  // yGroup.call(yAxis).select(".domain").remove();


  var line = d3.line()
      .x(function(d) { return x(d.date) })
      .y(function(d) { return y(d.availableCount) })

  xAxis = g.append("g")
      .attr("transform", "translate(0," + height + ")")
      .call(d3.axisBottom(x))
    // .select(".domain")
    //   .remove()
  // var xAxis = d3.axisBottom(x);

  // g.append("g")
  //     .attr("transform", "translate(0," + height + ")")
  //     .call(customXAxis);
  //
  // function customXAxis(g) {
  //   g.call(xAxis);
  //   g.select(".domain").remove();
  // }

  yAxis = g.append("g")
    .call(d3.axisLeft(y))
  .append('text')
      .attr('fill', color.white)
      .attr('x', 20)
      .attr('y', 0)
      .attr('dy', '0.5em')
      // .attr("text-anchor", "end")
      .text(txt.amount[lang]) // Tietoaineistoja


  // g.append("g")
  //     .call(customYAxis);
  //
  // function customYAxis(g) {
  //   g.call(yAxis);
  //   g.select(".domain").remove();
  //   g.selectAll(".tick:not(:first-of-type) line").attr("stroke", "#777").attr("stroke-dasharray", "2,2");
  //   g.selectAll(".tick text").attr("x", 4).attr("dy", -4);
  // }

  var yGroup = g.append("g")

  var xGroup = g.append("g")
      .attr("transform", "translate(0," + height + ")");


  var areaPath = g.append("path")
      .attr("clip-path", "url(#clip)")
      .attr("fill", "steelblue");


  // var area = d3.area()
  //   .curve(d3.curveStepAfter)
  //   .y0(y(0))
  //   .y1(function(d) { return y(d.availableCount); });

  // function onZoom() {
  //   var xz = d3.event.transform.rescaleX(x)
  //   xGroup.call(xAxis.scale(xz))
  //
  //   areaPath.attr("d", area.x(function(d) { return xz(d.date) }))
  // }

  g.append("path")
    .datum(this.data)
    .attr('fill', 'none')
    .attr("stroke", color.white)
    .attr("stroke-linejoin", "round")
    .attr("stroke-linecap", "round")
    .attr("stroke-width", 3)
    .attr("d", line)

  // zoomRect.call(zoom.transform, d3.zoomIdentity);

}
