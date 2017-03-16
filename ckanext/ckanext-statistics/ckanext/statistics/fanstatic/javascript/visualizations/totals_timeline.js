function TotalsTimeline (statistics, element, title, schema, settings) {
  var self = this
  self.statistics = statistics
  self.element = element
  self.schema = schema
  self.settings = settings

  // Data specifically formatted for the Totals timeline visualization
  self.data = {
    raw: [],
    line: [],
    histogram: {
      monthly: [],
      yearly: [],
    },
  }

  self.visual = {}
  self.visual.histogramType = 'monthly'

  self.size = {
    image: {},
    data: {},
  }

  self.renderBase(title)
  self.renderInputs()
}


// Update all: language, data visuals, input elements, etc.
TotalsTimeline.prototype.updateAll = function (items, firstDataLoad = false) {
  var self = this
  self.data.raw = items
  if (self.settings.organizations) {
    self.updateOrganizationSelector()
  }
  self.transformLineData()
  if (firstDataLoad) {
    self.renderAxisX()
    self.renderAxisY()
    self.resizeAxis('x', self.statistics.data.dateRange)
    self.initValues()
    self.renderFocus()
    self.eventListeners()
  }
  self.transformHistogramData()
  self.renderData()
}


TotalsTimeline.prototype.updateOrganizationSelector = function () {
  var self = this

  // Remove all existing options
  self.inputs.organization.selectAll('option').remove()

  // Refresh organization options
  self.inputs.organization.append('option')
    .text(self.statistics.translations.all[self.statistics.config.locale])
    .attr('value', '')

  for (i in self.statistics.data.organizations) {
    self.inputs.organization.append('option')
      .text(self.statistics.data.organizations[i].display_name)
      .attr('value', self.statistics.data.organizations[i].id)
  }
}


// Turns a list of items with publishing dates (datasets, apps, etc.) into a list of dates with cumulative number of published items on each date
TotalsTimeline.prototype.transformAllData = function () {
  var self = this
  self.transformLineData()
  self.transformHistogramData()
}


TotalsTimeline.prototype.transformLineData = function () {
  var self = this
  if (self.settings.organizations) {
    var organization = self.inputs.organization.property('value') || ''
  } else {
    organization = ''
  }

  result = {}

  startDate = moment.utc(self.statistics.data.dateRange[0]).toDate()
  endDate = moment.utc(self.statistics.data.dateRange[1]).toDate()

  // First, create an empty datatable with the correct datespan
  dateToAdd = startDate
  while (dateToAdd <= endDate) {
    dateString = moment.utc(dateToAdd).format('YYYY-MM-DD')
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
      item[self.schema.dateField] > moment.utc(endDate).format('YYYY-MM-DD') ||
      (organization !== '' && organization !== item.organization.id)
    ) {
      return true
    }
    itemDate = moment.utc(item[self.schema.dateField]).format('YYYY-MM-DD')
    // Add creation of this item
    result[itemDate].added.push(item[self.schema.nameField][self.statistics.config.locale])
  })

  // TODO: Get data from removed items
  //   result[item.date_modified].removed.push(item.title_translated[config.locale])

  // Calculate cumulative values
  dateToCumulate = startDate
  cumulativeValue = 0
  while (dateToCumulate <= endDate) {
    dateString = moment.utc(dateToCumulate).format('YYYY-MM-DD')
    cumulativeValue = cumulativeValue + result[dateString].added.length - result[dateString].removed.length
    result[dateString].value = cumulativeValue
    dateToCumulate = moment.utc(dateToCumulate).add(1, 'days').toDate()
  }

  // Turn into array
  resultFormatted = []
  for (i in result) {
    resultFormatted.push(result[i])
  }
  self.data.line = resultFormatted
  console.log('Transformed line data for totals timeline:', self.data.line)
}


TotalsTimeline.prototype.transformHistogramData = function () {
  var self = this
  self.data.histogram = {
    yearly: [],
    monthly: [],
  }
  var lastDate = self.statistics.data.dateRange[1]
  if (self.settings.organizations) {
    var organization = self.inputs.organization.property('value') || ''
  } else {
    organization = ''
  }

  // var bisectDate = d3.bisector(function(d) {
  //   return moment(d[self.schema.dateField]).format('YYYY-MM-DD')
  // }).left
  // Function to create monthly/yearly histogram bins
  function createHistogramBins(id, first, second, last, getNext) {
    var current = first
    var next = second
    while (current.toDate() < last.toDate()) {
      var value = 0

      // Calculate items in this histogram bin
      for (i in self.data.raw) {
        item = self.data.raw[i]
        if (
          moment.utc(item[self.schema.dateField]).toDate() >= current.toDate()
          && moment.utc(item[self.schema.dateField]).toDate() < next.toDate()
          && !self.schema.skip(item)
          && (organization === '' || organization === item.organization.id)
        ) {
          value++
        }

      }

      self.data.histogram[id].push({
        x0: current.toDate(),
        x1: next.toDate(),
        length: value,
      })
      current = next
      next = getNext(next)
      if (next > last) {
        next = last
      }
    }
  }

  // Monthly histogram
  createHistogramBins(
    'monthly',
    self.statistics.data.dateRange[0],
    moment.utc([
      self.statistics.data.dateRange[0].year(),
      self.statistics.data.dateRange[0].month(),
      1
    ]).add(1, 'months'),
    lastDate,
    function (date) {
      return moment.utc(date).add(1, 'months')
    }
  )
  // Yearly histogram
  createHistogramBins(
    'yearly',
    self.statistics.data.dateRange[0],
    moment.utc([self.statistics.data.dateRange[0].year() + 1, 0, 1]),
    lastDate,
    function (date) {
      return moment.utc(date).add(1, 'years')
    }
  )

  console.log('histogram bins', self.data.histogram)
}


TotalsTimeline.prototype.renderBase = function (title) {
  var self = this

  // Add HTML elements
  self.title = self.element.append('h3')
    .text(title[self.statistics.config.locale])
    .classed('statistics-vis-title')

  // Space needed on the page
  self.size.image.width = parseInt(self.statistics.styles.contentWidth)
  self.size.image.height = 360

  // Space for drawing the actual data
  self.size.margin = {top: 15, right: 50, bottom: 30, left: 1}
  self.size.data.width = self.size.image.width - self.size.margin.left - self.size.margin.right
  self.size.data.height = self.size.image.height - self.size.margin.top - self.size.margin.bottom

  // For SVG items
  self.visual.svg = self.element.append('svg')
    .attr('width', self.size.image.width)
    .attr('height', self.size.image.height)

  // Behind data items: ticks
  // Not clipped outside data area
  self.visual.extrasBackLayer = self.visual.svg.append('g')
    .attr('transform', 'translate(' + self.size.margin.left + ',' + self.size.margin.top + ')')

  // Data lines, bars, etc. - clipped if go outside data area
  self.visual.dataCanvas = self.visual.svg.append('g')
    .attr('transform', 'translate(' + self.size.margin.left + ',' + self.size.margin.top + ')')
    .attr('clip-path', 'url(#totals-timeline-data-clipper)')

  // Cuts out the data elements outside the axes
  self.visual.dataClipper = self.visual.dataCanvas
    .append('clipPath')
      .attr('transform', 'translate(-1, -1)')
      .attr('id', 'totals-timeline-data-clipper')
    .append('rect')
      .attr('width', self.size.data.width + 1)
      .attr('height', self.size.data.height + 1)

  self.visual.histogramCanvas = self.visual.dataCanvas.append('g')
  self.visual.lineCanvas = self.visual.dataCanvas.append('g')

  // Axes, legends, titles etc. in front of data items
  // And not clipped outside data area
  self.visual.extrasFrontLayer = self.visual.svg.append('g')
    .attr('transform', 'translate(' + self.size.margin.left + ',' + self.size.margin.top + ')')

  // For HTML items
  // self.visual.html = self.element.append('div')
  //   .attr('width', self.size.image.width)
  //   .attr('height', self.size.image.height)

}


TotalsTimeline.prototype.initValues = function () {
  var self = this
  // Draws the line when given data
  self.visual.lineDrawer = d3.line()
    .x(function(d) { return self.visual.xScale(d.date) })
    .y(function(d) { return self.visual.yScale(d.value) })

  self.inputs.startDate
    .property('value', self.statistics.data.dateRange[0].format('D.M.YYYY'))
  self.inputs.endDate
    .property('value', self.statistics.data.dateRange[1].format('D.M.YYYY'))
}


TotalsTimeline.prototype.renderInputs = function () {
  var self = this

  self.inputs = {}
  self.inputs.startDate = self.element.append('input')
    // .attr('type', 'date')
    .classed('statistics-input-date', true)
    .attr('placeholder', self.statistics.translations.datePlaceholder[self.statistics.config.locale])

  self.inputs.endDate = self.element.append('input')
  // .attr('type', 'date')
  .classed('statistics-input-date', true)
  .attr('placeholder', self.statistics.translations.datePlaceholder[self.statistics.config.locale])

  // self.inputs.downloadButton = self.element.append('button')
  //   .text(self.statistics.translations.downloadButton[self.statistics.config.locale])
  //   .classed('statistics-download-button', true)

  if (self.settings.organizations) {
    self.inputs.organization = self.element.append('select')
    .classed('choose-organization', true)
  }
}


TotalsTimeline.prototype.renderAxisX = function () {
  var self = this
  self.visual.xExtent = self.statistics.data.dateRange
  self.visual.xScale = d3.scaleTime()
    .domain(self.visual.xExtent)
    .rangeRound([0, self.size.data.width])

  self.visual.xAxisGenerator = d3.axisBottom(self.visual.xScale)
  self.visual.xAxis = self.visual.extrasFrontLayer.append("g")
    .attr('transform', 'translate(0,' + self.size.data.height + ')')
    .attr('class', 'statistics-axis')
    .call(self.visual.xAxisGenerator)
}


TotalsTimeline.prototype.renderAxisY = function () {
  var self = this
  self.visual.yExtent = [
    // Y min = 0 or lower
    Math.min(0, Math.round(d3.min(self.data.line, function(d) { return d.value }) * 1.25)),
    // Y max = max value + some margin
    Math.round(d3.max(self.data.line, function(d) { return d.value }) * 1.25 + 1)
  ]
  self.visual.yScale = d3.scaleLinear()
    .domain(self.visual.yExtent)
    .rangeRound([self.size.data.height, 0])

  self.visual.yAxisGeneratorBasis = d3.axisRight(self.visual.yScale)
    .tickSize(self.size.data.width)
    .tickValues(d3.range(self.visual.yScale.domain().slice(-1)[0] + 1))
    .tickFormat(function(d) {
      return this.parentNode.nextSibling
          ? '\xa0' + d
          : d + ' ' + self.statistics.translations.amount[self.statistics.config.locale];
    })

  self.visual.yAxisGenerator = function (g) {
    g.call(self.visual.yAxisGeneratorBasis)

    // Remove vertical line
    g.select('.domain').remove()

    g.selectAll('.tick:not(:first-of-type) line')
      .attr('stroke-dasharray', '2,2')

    // Move texts to the right side of the graph
    g.selectAll(".tick text").attr("x", self.size.data.width + 2).attr("dy", 2)
  }

  self.visual.yAxis = self.visual.extrasBackLayer.append("g")
    .attr('class', 'statistics-axis')
    .call(self.visual.yAxisGenerator)
}


TotalsTimeline.prototype.renderFocus = function () {
  var self = this
  // Focus point on the graph, changed by mouseover
  self.visual.focus = self.visual.extrasFrontLayer.append('g')
    .attr('class', 'focus')
    .attr('opacity', 0.9)
    .attr('fill', 'white')
    .attr('stroke', 'white')

  self.visual.focus.append('circle')
    .attr('r', 4.5)
    .attr({
      fill: 'white',
      stroke: 'white'
    })

  self.visual.focus.append('line')
    .classed('y', true)
    .attr({
      fill: 'none',
      'stroke': 'white',
      'stroke-width': '1.5px',
      'stroke-dasharray': '3 3'
    })

  self.visual.focus.append('text')
    .attr('class', 'js-datavis-count')
    .attr('x', -80)
    .attr('dy', '-30px')
    .style('font-size', '24px')

  self.visual.focus.append('text')
    .attr('class', 'js-datavis-date')
    .attr('x', -80)
    .attr('dy', '-12px')
    .style('font-size', '12px')

  var bisectDate = d3.bisector(function(d) { return d.date }).left

  var updateFocus = function () {
    var x0 = self.visual.xScale.invert(d3.mouse(this)[0])
    var i = bisectDate(self.data.line, x0, 1)
    var d0 = self.data.line[i - 1]
    var d1 = self.data.line[i]
    if (!d1) {
      return
    }
    var d = x0 - d0.date > d1.date - x0 ? d1 : d0
    self.setFocusDate(d)
  }

  self.visual.mouseEvents = self.visual.svg
    .append('rect')
    .attr('width', self.size.data.width)
    .attr('height', self.size.data.height + 25)
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
    if (self.data.line.length > 0) {
      var endDate = self.statistics.helpers.validDate(self.inputs.endDate.property('value'))

      if (!endDate) {
        d = self.data.line[self.data.line.length - 1]
      } else {
        var bisector =
        d = self.data.line[
          d3.bisector(function (point) {
            return point.date
          }).left(self.data.line, endDate)
        ]
        if (!d) {
          d = self.data.line[self.data.line.length - 1]
        }
      }
    } else {
      return
    }
  }

  self.visual.focus.attr('transform', 'translate(' + self.visual.xScale(d.date) + ', ' +  self.visual.yScale(d.value) + ')')
  self.visual.focus.select('line.y')
    .attr('x1', 0)
    .attr('x2', 0)
    .attr('y1', -self.visual.yScale(d.value))
    .attr('y2', -self.visual.yScale(d.value) + self.size.data.height);
'transform', 'translate(' + self.visual.xScale(d.date) + ', ' +  self.visual.yScale(d.value) + ')'
  self.visual.focus.select('.js-datavis-count').html(d.value)
  self.visual.focus.select('.js-datavis-date').html(moment.utc(d.date).format('D.M.YYYY'))
}


// Update data-dependent visual elements eg. line or bars
// Run on page load and after changing organization filter
TotalsTimeline.prototype.renderData = function () {
  var self = this
  self.renderHistogram()
  self.renderLine()
}

// Create histogram bars
TotalsTimeline.prototype.renderHistogram = function () {
  var self = this
  if (self.visual.histogramBars) {
    self.visual.histogramBars.remove()
  }

  self.visual.histogramBars = self.visual.histogramCanvas.selectAll('.bar')
      .data(self.data.histogram[self.visual.histogramType])
    .enter().append('g') // '.statistics-totals-timeline-line'
      .attr('class', 'bar')
      .attr('transform', function(d) { return 'translate(' + self.visual.xScale(d.x0) + ',' + self.visual.yScale(d.length) + ')' })

  // self.visual.histogramBars.exit().remove()

  self.visual.histogramBars.append('rect')
    .attr('x', 1)
    .attr(
      'width', function (d) {
        return self.visual.xScale(d.x1) - self.visual.xScale(d.x0) - 1
      }
    )
    .attr('height', function(d) { return self.size.data.height - self.visual.yScale(d.length) })

  self.visual.histogramBars.append('text')
    .attr('dy', '.75em')
    .attr('y', 6)
    .attr('x', function (d) {
      return (self.visual.xScale(d.x1) - self.visual.xScale(d.x0)) / 2
    })
    .attr('text-anchor', 'middle')
    .text(function(d) {
      return d.length > 0 ? ('+' + d.length) : d.length
    })
}

// Create the line
TotalsTimeline.prototype.renderLine = function () {
  var self = this
  if (self.visual.line) {
    self.visual.line.remove()
  }
  self.visual.line = self.visual.lineCanvas.append("path")
    .datum(self.data.line)
    .attr('d', self.visual.lineDrawer)
    .classed('statistics-line', true)
    .classed('statistics-totals-timeline-line', true)
}


TotalsTimeline.prototype.resizeAxis = function (axis, newExtent) {
  var self = this

  clearTimeout(self.visual['resizeAxisTimeout' + axis])
  self.visual['resizeAxisTimeout' + axis] = setTimeout(function () {

    // Init transition for each selected item (= only one axis)
    self.visual[axis + 'Axis'].transition().duration(800)
    .tween('resizeAxisTween', function (d, i) {
      self.visual.yAxis.select('.domain').remove()

      var axisScaleInterpolator = d3.interpolate(self.visual[axis + 'Extent'], newExtent)

      self.visual[axis + 'Extent'] = newExtent

      // Change histogram between monthly/yearly if necessary
      var duration = moment.utc(newExtent[1]).diff(moment.utc(newExtent[0]), 'years', true)
      if (duration >= 2.5 && self.visual.histogramType == 'monthly') {
        self.visual.histogramType = 'yearly'
        self.renderHistogram()
        // self.visual.histogramBars.data(self.data.histogram[self.visual.histogramType])

      } else if (duration < 2.5 && self.visual.histogramType == 'yearly') {
        self.visual.histogramType = 'monthly'
        self.renderHistogram()
        // self.visual.histogramBars.data(self.data.histogram[self.visual.histogramType])
      }

      // What to do on each step
      return function (t) {
        // Update scale
        self.visual[axis + 'Scale'].domain(axisScaleInterpolator(t))

        // Redraw axis
        self.visual[axis + 'Axis'].call(self.visual[axis + 'AxisGenerator'])

        // Redraw the line (will use the new y or x scale)
        self.visual.line.attr('d', self.visual.lineDrawer)

        // Redraw histogram
        self.visual.histogramBars
            .attr('transform', function(d) { return 'translate(' + self.visual.xScale(d.x0) + ',' + self.visual.yScale(d.length) + ')' });

        self.visual.histogramBars.select('rect')
          .attr(
            'width', function (d) {
              return self.visual.xScale(d.x1) - self.visual.xScale(d.x0) - 1
            }
          )
          .attr('height', function(d) { return self.size.data.height - self.visual.yScale(d.length) })

        self.visual.histogramBars.select('text')
          .attr('x', function (d) {
            return (self.visual.xScale(d.x1) - self.visual.xScale(d.x0)) / 2
          })

        // Focus point
        self.setFocusDate()
      }
    })
  }, 100)
}


TotalsTimeline.prototype.onAreaResize = function () {
  var self = this
  self.size.image.width = parseInt(self.statistics.styles.contentWidth)

  self.size.image.height = 360
  self.size.data.width = self.size.image.width - self.size.margin.left - self.size.margin.right
  self.size.data.height = self.size.image.height - self.size.margin.top - self.size.margin.bottom
  self.visual.svg
    .attr('width', self.size.image.width)
    .attr('height', self.size.image.height)

  self.visual.extrasBackLayer
    .attr('transform', 'translate(' + self.size.margin.left + ',' + self.size.margin.top + ')')

  // Data lines, bars, etc. - clipped if go outside data area
  self.visual.dataCanvas
    .attr('transform', 'translate(' + self.size.margin.left + ',' + self.size.margin.top + ')')

  // Cuts out the data elements outside the axes
  self.visual.dataClipper
    .attr('width', self.size.data.width + 1)
    .attr('height', self.size.data.height + 1)

  self.visual.extrasFrontLayer
    .attr('transform', 'translate(' + self.size.margin.left + ',' + self.size.margin.top + ')')

  self.updateAll(self.data.raw)
}


// USER INTERACTIONS

TotalsTimeline.prototype.eventListeners = function () {
  var self = this

  var readDateInputs = function () {
    var startDate = ''
    if (self.inputs.startDate.property('value') == '') {
      startDate = self.statistics.data.dateRange[0]
    } else {
      startDate = self.statistics.helpers.validDate(self.inputs.startDate.property('value'))
      if (!startDate) {
        return false
      }
    }
    var endDate = ''
    if (self.inputs.endDate.property('value') == '') {
      endDate = self.statistics.data.dateRange[1]
    } else {
      endDate = self.statistics.helpers.validDate(self.inputs.endDate.property('value'))
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

  if (self.settings.organizations) {
    self.inputs.organization.on('change', function () {
      var organization = self.inputs.organization.property('value')
      console.log('Organization changed', organization)
      self.transformAllData()
      self.renderData()
      self.resizeAxis('y', [
        // Y min = 0 or lower
        Math.min(0, Math.round(d3.min(self.data.line, function(d) { return d.value }) * 1.25 + 1)),
        // Y max = max value + some margin
        Math.round(d3.max(self.data.line, function(d) { return d.value }) * 1.25 + 1)
      ])
    })
  }

  // self.inputs.downloadButton.on('click', function () {
  //   console.log('clicked download')
  // })
}
