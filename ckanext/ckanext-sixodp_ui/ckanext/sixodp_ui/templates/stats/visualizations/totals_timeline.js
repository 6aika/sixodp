function TotalsTimeline (dashboard, element, title, schema, features) {
  var self = this
  self.dashboard = dashboard
  self.element = element
  self.schema = schema
  self.features = features

  // Data specifically formatted for the Totals timeline visualization
  self.data = {
    raw: [],
    timeline: [],
  }

  self.renderBase(title)
  self.renderInputs()
  // self.renderAxes()
}


// Update all: language, data visuals, input elements, etc.
TotalsTimeline.prototype.updateAll = function (items, firstDataLoad = false) {
  var self = this
  self.data.raw = items
  if (self.features.organizations) {
    self.updateOrganizationSelector()
  }
  self.transformData()
  if (firstDataLoad) {
    self.renderAxisX()
    self.renderAxisY()
    self.resizeAxis('x', self.dashboard.data.dateRange)
    self.initLineDrawer()
    self.renderFocus()
    self.eventListeners()
  }
  self.renderData()
}


TotalsTimeline.prototype.updateOrganizationSelector = function () {
  var self = this

  // Remove all existing options
  self.inputs.organization.selectAll('option').remove()

  // Refresh organization options
  self.inputs.organization.append('option')
    .text(self.dashboard.translations.all[self.dashboard.config.locale])
    .attr('value', '')

  for (i in self.dashboard.data.organizations) {
    self.inputs.organization.append('option')
      .text(self.dashboard.data.organizations[i].display_name)
      .attr('value', self.dashboard.data.organizations[i].id)
  }
}


// Turns a list of items with publishing dates (datasets, apps, etc.) into a list of dates with cumulative number of published items on each date
TotalsTimeline.prototype.transformData = function () {
  var self = this
  if (self.features.organizations) {
    var organization = self.inputs.organization.property('value') || ''
  } else {
    organization = ''
  }

  result = {}

  startDate = moment.utc(self.dashboard.data.dateRange[0]).toDate()
  endDate = moment.utc(self.dashboard.data.dateRange[1]).toDate()

  // First, create an empty datatable with the correct datespan
  dateToAdd = startDate
  while (dateToAdd <= endDate) {
    dateString = moment(dateToAdd).format('YYYY-MM-DD')
    result[dateString] = {
      date: dateToAdd,
      added: [],
      removed: [],
      value: undefined,
    }
    dateToAdd = moment.utc(dateToAdd).add(1, 'days').toDate()
  }

  // Add each item to its creation date
  self.data.raw.forEach(function(item) {
    // Skip private items completely
    if (
      self.schema.skip(item) ||
      item[self.schema.dateField] > moment(endDate).format('YYYY-MM-DD') ||
      (organization !== '' && organization !== item.organization.id)
    ) {
      return true
    }
    itemDate = moment.utc(item[self.schema.dateField]).format('YYYY-MM-DD')
    // Add creation of this item
    result[itemDate].added.push(item[self.schema.nameField][self.dashboard.config.locale])
  })

  // TODO: Get data from removed items
  //   result[item.date_modified].removed.push(item.title_translated[config.locale])

  // Calculate cumulative values
  dateToCumulate = startDate
  cumulativeValue = 0
  while (dateToCumulate <= endDate) {
    dateString = moment(dateToCumulate).format('YYYY-MM-DD')
    cumulativeValue = cumulativeValue + result[dateString].added.length - result[dateString].removed.length
    result[dateString].value = cumulativeValue
    dateToCumulate = moment.utc(dateToCumulate).add(1, 'days').toDate()
  }

  // Turn into array
  resultFormatted = []
  for (i in result) {
    resultFormatted.push(result[i])
  }
  self.data.timeline = resultFormatted
  console.log('Transformed data for totals timeline:', self.data.timeline)
}


TotalsTimeline.prototype.renderBase = function (title) {
  var self = this

  // Add HTML elements
  self.title = self.element.append('h3')
    .text(title[self.dashboard.config.locale])
    .classed('dashboard-vis-title')

  // Space needed on the page
  self.imageWidth = self.dashboard.styles.contentWidth
  self.imageHeight = 360

  // Space for drawing the actual data
  self.margin = {top: 15, right: 50, bottom: 30, left: 1}
  self.dataWidth = self.imageWidth - self.margin.left - self.margin.right
  self.dataHeight = self.imageHeight - self.margin.top - self.margin.bottom

  // SVG
  self.svg = self.element.append('svg')
    .attr('width', self.imageWidth)
    .attr('height', self.imageHeight)

  // Visualization = data line, axes, legends, etc.
  self.vis = self.svg.append("g")
    .attr("transform", "translate(" + self.margin.left + "," + self.margin.top + ")")

  // Cuts the line outside the axes
  self.lineClipper = self.vis.append('clipPath')
      .attr('transform', 'translate(-1, -1)')
      .attr('id', 'totals-timeline-data-clipper')
    .append('rect')
      .attr('width', self.dataWidth + 1)
      .attr('height', self.dataHeight + 1)
}


TotalsTimeline.prototype.initLineDrawer = function () {
  // Draws the line when given data
  self.lineDrawer = d3.line()
    .x(function(d) { return self.xScale(d.date) })
    .y(function(d) { return self.yScale(d.value) })
}


TotalsTimeline.prototype.renderInputs = function () {
  var self = this

  self.inputs = {}
  self.inputs.startDate = self.element.append('input')
    .attr('type', 'date')
    .attr('placeholder', self.dashboard.translations.datePlaceholder[self.dashboard.config.locale])

  self.inputs.endDate = self.element.append('input')
    .attr('type', 'date')
    .attr('placeholder', self.dashboard.translations.datePlaceholder[self.dashboard.config.locale])

  self.inputs.downloadButton = self.element.append('button')
    .text(self.dashboard.translations.downloadButton[self.dashboard.config.locale])
    .classed('dashboard-download-button', true)

  if (self.features.organizations) {
    self.inputs.organization = self.element.append('select')
    .classed('choose-organization', true)
  }
}


TotalsTimeline.prototype.renderAxisX = function () {
  var self = this
  self.xExtent = self.dashboard.data.dateRange
  self.xScale = d3.scaleTime()
    .domain(self.xExtent)
    .rangeRound([0, self.dataWidth])

  self.xAxisGenerator = d3.axisBottom(self.xScale)
  self.xAxis = self.vis.append("g")
    .attr('transform', 'translate(0,' + self.dataHeight + ')')
    .attr('class', 'dashboard-axis')
    .call(self.xAxisGenerator)
}


TotalsTimeline.prototype.renderAxisY = function () {
  var self = this
  self.yExtent = [
    // Y min = 0 or lower
    Math.min(0, Math.round(d3.min(self.data.timeline, function(d) { return d.value }) * 1.25)),
    // Y max = max value + some margin
    Math.round(d3.max(self.data.timeline, function(d) { return d.value }) * 1.25 + 1)
  ]
  self.yScale = d3.scaleLinear()
    .domain(self.yExtent)
    .rangeRound([self.dataHeight, 0])

  self.yAxisGeneratorBasis = d3.axisRight(self.yScale)
    .tickSize(self.dataWidth)
    .tickValues(d3.range(self.yScale.domain().slice(-1)[0] + 1))
    .tickFormat(function(d) {
      return this.parentNode.nextSibling
          ? "\xa0" + d
          : d + ' ' + self.dashboard.translations.amount[self.dashboard.config.locale];
    })

  self.yAxisGenerator = function (g) {
    g.call(self.yAxisGeneratorBasis)

    // Remove vertical line
    g.select('.domain').remove()

    g.selectAll(".tick:not(:first-of-type) line")
      .attr("stroke-dasharray", "2,2")

    // Move texts to the right side of the graph
    g.selectAll(".tick text").attr("x", self.dataWidth).attr("dy", 2)
  }

  self.yAxis = self.vis.append("g")
    .attr('class', 'dashboard-axis')
    .call(self.yAxisGenerator)
}


TotalsTimeline.prototype.resizeAxis = function (axis, newExtent) {
  var self = this

  clearTimeout(self['resizeAxisTimeout' + axis])
  self['resizeAxisTimeout' + axis] = setTimeout(function () {
    self[axis + 'Axis'].transition().duration(800).tween('axis', function (d, i) {

      self.yAxis.select('.domain').remove()

      var interpolator = d3.interpolate(self[axis + 'Extent'], newExtent)
      self[axis + 'Extent'] = newExtent
      return function (t) {
        self[axis + 'Scale'].domain(interpolator(t))
        self[axis + 'Axis'].call(self[axis + 'AxisGenerator'])
        self.lineDrawer = d3.line()
          .x(function(d) { return self.xScale(d.date) })
          .y(function(d) { return self.yScale(d.value) })
        self.line.attr('d', self.lineDrawer)

        self.setFocusDate()
      }
    })
  }, 100)
}


TotalsTimeline.prototype.renderFocus = function () {
  var self = this
  // Focus point on the graph, changed by mouseover
  self.focus = self.vis.append('g')
    .attr('class', 'focus')
    .attr('opacity', 0.9)
    .attr('fill', 'white')
    .attr('stroke', 'white')

  self.focus.append('circle')
    .attr('r', 4.5)
    .attr({
      fill: 'white',
      stroke: 'white'
    })

  self.focus.append('line')
    .classed('y', true)
    .attr({
      fill: 'none',
      'stroke': 'white',
      'stroke-width': '1.5px',
      'stroke-dasharray': '3 3'
    })

  self.focus.append('text')
    .attr('class', 'js-datavis-count')
    .attr('x', -80)
    .attr('dy', '-30px')
    .style('font-size', '24px')

  self.focus.append('text')
    .attr('class', 'js-datavis-date')
    .attr('x', -80)
    .attr('dy', '-12px')
    .style('font-size', '12px')

  bisectDate = d3.bisector(function(d) { return d.date }).left

  var updateFocus = function () {
    var x0 = self.xScale.invert(d3.mouse(this)[0])
    var i = bisectDate(self.data.timeline, x0, 1)
    var d0 = self.data.timeline[i - 1]
    var d1 = self.data.timeline[i]
    if (!d1) {
      return
    }
    var d = x0 - d0.date > d1.date - x0 ? d1 : d0
    self.setFocusDate(d)
  }

  self.vis
    .append('rect')
    .classed('overlay', true)
    .attr('width', self.dataWidth)
    .attr('height', self.dataHeight + 25)
    .attr('fill', 'none')
    .attr('pointer-events', 'all')
    .on('mouseout', function () {
      return self.setFocusDate()
    })
    .on('mousemove', updateFocus)

  self.setFocusDate()
}


TotalsTimeline.prototype.setFocusDate = function (d) {
  var self = this

  if (!d) {
    if (self.data.timeline.length > 0) {
      var endDate = self.dashboard.helpers.validDate(self.inputs.endDate.property('value'))

      if (!endDate) {
        d = self.data.timeline[self.data.timeline.length - 1]
      } else {
        endDate = endDate.format('YYYY-MM-DD')
        for (i in self.data.timeline) {
          d = self.data.timeline[i]
          if (moment.utc(d.date).format('YYYY-MM-DD') == endDate) {
            break
          }
        }
      }
    } else {
      return
    }
  }

  self.focus.attr('transform', 'translate(' + self.xScale(d.date) + ', ' +  self.yScale(d.value) + ')')
  self.focus.select('line.y')
    .attr('x1', 0)
    .attr('x2', 0)
    .attr('y1', -self.yScale(d.value))
    .attr('y2', -self.yScale(d.value) + self.dataHeight);
'transform', 'translate(' + self.xScale(d.date) + ', ' +  self.yScale(d.value) + ')'
  self.focus.select('.js-datavis-count').html(d.value)
  self.focus.select('.js-datavis-date').html(moment(d.date).format('D.M.YYYY'))
}


// Update data-dependent visual elements eg. line or bars
// Run on page load and after changing organization filter
TotalsTimeline.prototype.renderData = function () {
  var self = this
  if (self.line) {
    self.line.remove()
  }

  // Finally, create the line
  self.line = self.vis.insert('g', '.overlay')
    .attr('clip-path', function(d,i) { return 'url(#totals-timeline-data-clipper)' })
  .append("path")
    .datum(self.data.timeline)
    .attr('d', self.lineDrawer)
    .attr("class", "dashboard-line")
}



// USER INTERACTIONS

TotalsTimeline.prototype.eventListeners = function () {
  var self = this

  var readDateInputs = function () {
    var startDate = ''
    if (self.inputs.startDate.property('value') == '') {
      startDate = self.dashboard.data.dateRange[0]
    } else {
      startDate = self.dashboard.helpers.validDate(self.inputs.startDate.property('value'))
      if (!startDate) {
        return false
      }
    }
    var endDate = ''
    if (self.inputs.endDate.property('value') == '') {
      endDate = self.dashboard.data.dateRange[1]
    } else {
      endDate = self.dashboard.helpers.validDate(self.inputs.endDate.property('value'))
      if (!endDate) {
        return false
      }
    }
    if (startDate > endDate) {
      return false
    }
    self.resizeAxis('x', [startDate.toDate(), endDate.toDate()])
  }
  self.inputs.startDate.on('keyup', readDateInputs)
  self.inputs.endDate.on('keyup', readDateInputs)

  if (self.features.organizations) {
    self.inputs.organization.on('change', function () {
      var organization = self.inputs.organization.property('value')
      console.log('Organization changed', organization)
      self.transformData()
      self.renderData()
      self.resizeAxis('y', [
        // Y min = 0 or lower
        Math.min(0, Math.round(d3.min(self.data.timeline, function(d) { return d.value }) * 1.25)),
        // Y max = max value + some margin
        Math.round(d3.max(self.data.timeline, function(d) { return d.value }) * 1.25)
      ])
    })
  }

  self.inputs.downloadButton.on('click', function () {
    console.log('clicked download')
  })
}
