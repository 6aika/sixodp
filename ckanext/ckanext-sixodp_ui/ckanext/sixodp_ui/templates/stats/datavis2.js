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
  console.log('Datavis 2 data:', self.data)
}



// RENDER THE DATA VISUALIZATION

datavis2.render = function () {
  var self = this
  var margin = {top: 20, right: 50, bottom: 30, left: 0}

  var imageWidth = contentWidth
  var imageHeight = 360

  var dataWidth = imageWidth - margin.left - margin.right
  var dataHeight = imageHeight - margin.top - margin.bottom

  var svg = d3.select('.js-datavis-2 .datavis-svg')
    .attr('width', imageWidth)
    .attr('height', imageHeight + 50)

  self.vis = svg.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")")

  // var parseTime = d3.timeParse("%y-%m-%d");
  self.xFullExtent = d3.extent(this.data, function(d) { return d.date })
  self.xExtent = self.xFullExtent

  self.inputs.startDate
    .property('value', moment(self.xFullExtent[0]).format('D.M.YYYY'))
    .attr('placeholder', moment(self.xFullExtent[0]).format('D.M.YYYY'))
  self.inputs.endDate
    .property('value', moment(self.xFullExtent[1]).format('D.M.YYYY'))
    .attr('placeholder', moment(self.xFullExtent[1]).format('D.M.YYYY'))

  self.xScale = d3.scaleTime()
    .domain(self.xExtent)
    .rangeRound([0, dataWidth])

  self.yScale = d3.scaleLinear()
    .domain([
      Math.min(0, d3.min(self.data, function(d) { return d.availableCount })),
      d3.max(self.data, function(d) { return d.availableCount })
    ])
    .rangeRound([dataHeight, 0])

  self.xAxisGenerator = d3.axisBottom(self.xScale)
  self.xAxis = self.vis.append("g")
    .attr('transform', 'translate(0,' + dataHeight + ')')
    .call(self.xAxisGenerator)

  yAxis = self.vis.append("g")
    .attr('transform', 'translate(' + dataWidth + ', 0)')
    .call(d3.axisRight(self.yScale))
  .append('text')
    .attr('fill', color.white)
    .attr('x', 20)
    .attr('y', 0)
    .attr('dy', '0.5em')
    // .attr("text-anchor", "end")
    .text(texts.amount[lang]) // Tietoaineistoja

  self.lineDrawer = d3.line()
    .x(function(d) { return self.xScale(d.date) })
    .y(function(d) { return self.yScale(d.availableCount) })

  self.line = self.vis.append("path")
    .datum(self.data)
    .attr('fill', 'none')
    .attr("stroke", color.white)
    .attr("stroke-linejoin", "round")
    .attr("stroke-linecap", "round")
    .attr("stroke-width", 3)
    .attr("d", self.lineDrawer)
}

datavis2.resizeXAxis = function (xMin, xMax) {
  var self = this
  var newExtent = [xMin, xMax]
  console.log('ResizeXAxis!', newExtent)

  clearTimeout(self.resizeXAxisTimeout)
  self.resizeXAxisTimeout = setTimeout(function () {
    self.xAxis
      // .interrupt().selectAll('*').interrupt()
      .transition().duration(800).tween('axis', function (d, i) {
      // console.log('Transition tween', d, i)
      var interpolator = d3.interpolate(self.xExtent, newExtent)

      self.xExtent = newExtent
      return function (t) {
        // console.log('Transition callback, t =', t)
        self.xScale.domain(interpolator(t))
        self.xAxis.call(self.xAxisGenerator)
        self.lineDrawer = d3.line()
          .x(function(d) { return self.xScale(d.date) })
          .y(function(d) { return self.yScale(d.availableCount) })

        self.line.attr("d", self.lineDrawer)
      }
    })
  }, 100)
}



// USER INTERACTIONS

datavis2.eventListeners = function () {
  self.inputs.startDate.on('keyup', function () {
    console.log('keyup!', new Date())
    var startDate = validDate(self.inputs.startDate.property('value'))
    if (!startDate) {
      return false
    }
    var endDate = validDate(self.inputs.endDate.property('value'))
    if ((!endDate) || (startDate == self.xExtent[0]) || (startDate >= endDate)) {
      return false
    }
    self.resizeXAxis(startDate.toDate(), endDate.toDate())
  })

  self.inputs.endDate.on('keyup', function () {
    var endDate = validDate(self.inputs.endDate.property('value'))
    if (!endDate) {
      return false
    }
    var startDate = validDate(self.inputs.startDate.property('value'))
    if ((!startDate) || (endDate == self.xExtent[1]) || startDate >= endDate) {
      return false
    }
    self.resizeXAxis(startDate.toDate(), endDate.toDate())
  })

  self.inputs.downloadButton.on('click', function () {
    console.log('clicked download')
  })
}



// var yGroup = vis.append("g")
// var xGroup = vis.append("g")
//     .attr("transform", "translate(0," + height + ")");

// var areaPath = g.append("path")
//     .attr("clip-path", "url(#clip)")
//     .attr("fill", "steelblue");

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

// g.append("clipPath")
//     .attr("id", "clip")
//   .append("rect")
//     .attr("width", width)
//     .attr("height", height);

// zoom.translateExtent([
//   [x(xExtent[0]), -Infinity],
//   [x(xExtent[1]), Infinity]
// ])

// yGroup.call(yAxis).select(".domain").remove();


// g.append("g")
//     .attr("transform", "translate(0," + height + ")")
//     .call(customXAxis);
//
// function customXAxis(g) {
//   g.call(xAxis);
//   g.select(".domain").remove();
// }


// g.append("g")
//     .call(customYAxis);
//
// function customYAxis(g) {
//   g.call(yAxis);
//   g.select(".domain").remove();
//   g.selectAll(".tick:not(:first-of-type) line").attr("stroke", "#777").attr("stroke-dasharray", "2,2");
//   g.selectAll(".tick text").attr("x", 4).attr("dy", -4);
// }

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

// zoomRect.call(zoom.transform, d3.zoomIdentity);
