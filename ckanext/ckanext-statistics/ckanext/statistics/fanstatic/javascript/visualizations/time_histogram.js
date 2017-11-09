function TimeHistogram () {
  var self = this

  self._data =  {
    monthly: [],
    yearly: [],
  }
  self._state = {
    accuracy: 'month',
  }
}

TimeHistogram.prototype.transform = function () {
  var self = this
  self.data.histogram = {
    yearly: [],
    monthly: [],
  }
  var lastDate = self.statistics.data.dateRange[1]

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
          && (self.organization === '' || self.organization === item.organization.id)
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
};

TimeHistogram.prototype.setDateFilter = function (dates) {
  var self = this
  self._state.dateRange = dates
  console.log('Top histogram set date', dates)
  self._data = self._transData(data)

  // TODO:
  // Resize y axis
  // Choose between monthly and yearly histogram
  // Re-render histogram
};

TimeHistogram.prototype._renderBase = function () {
  self.visual.histogramCanvas = self._elem.dataCanvas.append('g')
};

// Create histogram bars
TimeHistogram.prototype.render = function () {
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
};

// Animate new size of y or x axis based on new date filter or organization/category filter
TotalsTimeline.prototype.resizeAxis = function (axis) {
  var self = this
  var newExtent = (axis === 'x' ? self._getXExtent() : self._getYExtent())

  clearTimeout(self._state['resizeAxisTimeout' + axis])
  self._state['resizeAxisTimeout' + axis] = setTimeout(function () {

    // Init transition for each selected item (= only one axis)
    self._elem[axis + 'Axis'].transition().duration(800)
    .tween('resizeAxisTween', function (d, i) {
      // self._elem.yAxis.select('.domain').remove()

      var axisScaleInterpolator = d3.interpolate(self.visual[axis + 'Extent'], newExtent)

      self.visual[axis + 'Extent'] = newExtent

      // Change histogram between monthly/yearly if necessary
      var duration = moment.utc(newExtent[1]).diff(moment.utc(newExtent[0]), 'years', true)
      if (duration >= 2.5 && self.visual.histogramType == 'monthly') {
        self.visual.histogramType = 'yearly'
        self.renderHistogram()
        self.visual.histogramBars.data(self.data.histogram[self.visual.histogramType])

      } else if (duration < 2.5 && self.visual.histogramType == 'yearly') {
        self.visual.histogramType = 'monthly'
        self.renderHistogram()
        self.visual.histogramBars.data(self.data.histogram[self.visual.histogramType])
      }

      // What to do on each step
      return function (t) {
        // Update scale
        self.visual[axis + 'Scale'].domain(axisScaleInterpolator(t))

        // Redraw axis
        self.visual[axis + 'Axis'].call(self.visual[axis + 'AxisGenerator'])

        // Redraw histogram
        self.visual.histogramBars
            .attr('transform', function(d) { return 'translate(' + self._helpers.xScale(d.x0) + ',' + self._helpers.yScale(d.length) + ')' });

        self.visual.histogramBars.select('rect')
          .attr(
            'width', function (d) {
              return self._helpers.xScale(d.x1) - self._helpers.xScale(d.x0) - 1
            }
          )
          .attr('height', function(d) { return self._state.dataArea.height - self._helpers.yScale(d.length) })

        self.visual.histogramBars.select('text')
          .attr('x', function (d) {
            return (self._helpers.xScale(d.x1) - self._helpers.xScale(d.x0)) / 2
          })
      }
    })
  }, 100)
}
