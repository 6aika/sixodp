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

  // Space needed on the page
  var imageWidth = contentWidth
  var imageHeight = 360

  // Space for drawing the actual data
  var margin = {top: 15, right: 50, bottom: 30, left: 1}
  var dataWidth = imageWidth - margin.left - margin.right
  var dataHeight = imageHeight - margin.top - margin.bottom

  var svg = d3.select('.js-datavis-2 .datavis-svg')
    .attr('width', imageWidth)
    .attr('height', imageHeight)

  // Visualization = data line, axises, legends, etc.
  self.vis = svg.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")")

  // Extent of x axis (date)
  self.xFullExtent = d3.extent(this.data, function(d) { return d.date })
  self.xExtent = self.xFullExtent
  self.inputs.startDate
    .property('value', moment(self.xFullExtent[0]).format('D.M.YYYY'))
    .attr('placeholder', moment(self.xFullExtent[0]).format('D.M.YYYY'))
  self.inputs.endDate
    .property('value', moment(self.xFullExtent[1]).format('D.M.YYYY'))
    .attr('placeholder', moment(self.xFullExtent[1]).format('D.M.YYYY'))

  // Create X and Y axis
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
    .attr('class', 'datavis-axis')
    .call(self.xAxisGenerator)

  self.yAxisGenerator = d3.axisRight(self.yScale)
    .tickSize(dataWidth)
    .tickFormat(function(d) {
      // var s = formatNumber(d);
      return this.parentNode.nextSibling
          ? "\xa0" + d
          : d + ' ' + texts.amount[lang];
    })

  self.customYAxis = function (g) {
    g.call(self.yAxisGenerator)

    // Remove vertical line
    g.select('.domain').remove()

    g.selectAll(".tick:not(:first-of-type) line")
      // .attr('class', 'datavis-scale-line')
      // .attr("stroke", 'red')
      .attr("stroke-dasharray", "2,2")

    // Move texts to the right
    g.selectAll(".tick text").attr("x", dataWidth).attr("dy", 2)
  }

  yAxis = self.vis.append("g")
    // .attr('transform', 'translate(' + dataWidth + ', 0)')
    .attr('class', 'datavis-axis')
    .call(self.customYAxis)


  // Draws the line when given data
  self.lineDrawer = d3.line()
    .curve(d3.curveBasis) // Smooth curve
    .x(function(d) { return self.xScale(d.date) })
    .y(function(d) { return self.yScale(d.availableCount) })

  // Cuts the line outside the axises
  self.lineClipper = self.vis.append('clipPath')
      .attr('transform', 'translate(-1, -1)')
      .attr('id', 'datavis2-data-clipper')
    .append('rect')
      .attr('width', dataWidth + 1)
      .attr('height', dataHeight + 1)

  // Finally, create the line
  self.line = self.vis.append('g')
    .attr('clip-path', function(d,i) { return 'url(#datavis2-data-clipper)' })
  .append("path")
    .datum(self.data)
    .attr('d', self.lineDrawer)
    .attr("class", "datavis-line")


  // Focus point on the graph, changed by mouseover
  var focus = self.vis.append('g')
    .attr('class', 'focus')
    // .style('display', 'none');

  focus.append('circle')
    .attr('r', 4.5);

  focus.append('line')
    .classed('x', true);

  focus.append('line')
    .classed('y', true);

  focus.append('text')
    .attr('x', 9)
    .attr('dy', '.35em');

  self.vis.append('rect')
    .attr('class', 'overlay')
    .attr('width', dataWidth)
    .attr('height', dataHeight)
    .on('mouseover', () => focus.style('display', null))
    .on('mouseout', () => focus.style('display', 'none'))
    .on('mousemove', onmousemove);

  d3.select('.overlay')
    .attr('fill', 'none')
    .attr('pointer-events', 'all')
    
  d3.selectAll('.focus')
    .attr('opacity', 0.7);

  d3.selectAll('.focus circle')
    .attr({
      fill: 'none',
      stroke: 'black'
    });

  d3.selectAll('.focus line')
    .attr({
      fill: 'none',
      'stroke': 'black',
      'stroke-width': '1.5px',
      'stroke-dasharray': '3 3'
    });

  var bisectDate = d3.bisector(function(d) { return d.date }).left;
  function onmousemove() {
    const x0 = self.xScale.invert(d3.mouse(this)[0]);
    const i = bisectDate(self.data, x0, 1);
    const d0 = self.data[i - 1];
    const d1 = self.data[i];
    const d = x0 - d0.date > d1.date - x0 ? d1 : d0;
    focus.attr('transform', `translate(${self.xScale(d.date)}, ${self.yScale(d.availableCount)})`);
    focus.select('line.x')
      .attr('x1', 0)
      .attr('x2', -self.xScale(d.date))
      .attr('y1', 0)
      .attr('y2', 0);

    focus.select('line.y')
      .attr('x1', 0)
      .attr('x2', 0)
      .attr('y1', 0)
      .attr('y2', dataHeight - self.yScale(d.availableCount));

    focus.select('text').text(d.availableCount);
  }
}

datavis2.resizeXAxis = function (xMin, xMax) {
  var self = this
  var newExtent = [xMin, xMax]
  // console.log('ResizeXAxis!', newExtent)

  clearTimeout(self.resizeXAxisTimeout)
  self.resizeXAxisTimeout = setTimeout(function () {
    self.xAxis.transition().duration(800).tween('axis', function (d, i) {
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

        self.line.attr('d', self.lineDrawer)
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
