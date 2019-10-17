// Class for visualizing "Top items" as a histogram
function TopHistogram (params) {
  var self = this;

  // Immutable
  self._texts = params.texts;
  self._props = {
    id: params.id,
    margin: $.extend({}, params.margin),
    legend: params.legend,
    dateFormat: 'YYYY-MM-DD' // Used in the data, not screen
  };
  if (params.legend.length > 0) {
    self._props.margin.top = 90;
  }
  self._elem = {};
  self._helpers = {};
  self._schema = params.schema;

  // Mutable
  var barHeight = params.barHeight || 50; // Default height
  var barCount = 10;
  var contentHeight = barHeight * barCount + self._props.margin.top + self._props.margin.bottom;

  self._state = {
    barCount: barCount,
    barHeight: barHeight,
    contentArea: {
      width: params.width,
      height: contentHeight
    },
    dataArea: {
      width: params.width - self._props.margin.left - self._props.margin.right,
      height: contentHeight - self._props.margin.top - self._props.margin.bottom
    },
    dateRange: [moment.utc().subtract(1, 'days'), moment.utc()],
    maxDateRange: [moment.utc().subtract(1, 'years'), moment.utc()],
    organization: '',
    category: ''
  };

  self._data = {};

  self._renderBase(params.element);
  self.resize(params.width, self._state.barHeight)
}

TopHistogram.prototype.getUrl = function(category) {
  var categoryMapping = {
    'format': ckan.LOCALE_ROOT + '/dataset?res_format=',
    'dataset': ckan.LOCALE_ROOT + '/dataset/',
    'category': ckan.LOCALE_ROOT + '/group/',
    'organization': ckan.LOCALE_ROOT + '/organization/',
    'app_category': ckan.LOCALE_ROOT + '/showcase?vocab_category_fi='
  };

  return categoryMapping[category];
};


// Whenever new data arrives
TopHistogram.prototype.setData = function (data) {
  var self = this;
  self._elem.container.select('.vis-notification').remove();

  // Skip update if no data exists
  if(data.length === 0) {
    self._elem.svg.style('display', 'none');
    self._elem.container.append('p')
      .attr('class', 'vis-notification')
      .text(this._texts.noDataText);

    return false;
  }

  self._elem.svg.style('display', 'block');
  self._data.raw = data;
  self._data.histogram = self._transformData(data);
  self._state.barCount = self._data.histogram.length;

  // Update y domain range based on amount of histgram bars
  self._helpers.yScale.domain(
    d3.map(self._data.histogram, function(d) { return d[self._schema.labelField] }).keys()
  );

  self.resize();

  // This will also update x domain range
  self._renderHistogram(self._data.histogram);
  return true;
};


// When window is resized
TopHistogram.prototype.setDateRange = function (dates) {
  var self = this;
  self._state.dateRange = dates;
  if (self._data.raw) {
    self.setData(self._data.raw);
  }
};

// Resize the visualization to a new pixel size on the screen
TopHistogram.prototype.resize = function (contentWidth, barHeight) {
  var self = this;

  // Image width
  if (typeof contentWidth !== 'undefined') {
    self._state.contentArea.width = contentWidth
  }
  self._elem.svg.attr('width', self._state.contentArea.width);

  // Space for bar labels
  self._props.margin.left = Math.max(self._state.contentArea.width * 0.2, 120);

  // Height per bar + spacing in between
  if (typeof barHeight !== 'undefined') {
    self._state.barHeight = barHeight
  }

  // Image height, based on current amount of bars
  self._state.contentArea.height = self._state.barHeight * self._state.barCount + self._props.margin.top + self._props.margin.bottom;

  self._elem.svg.attr('height', self._state.contentArea.height);

  // Move whole image
  self._elem.svgCanvas
  .attr('transform', 'translate(' + self._props.margin.left + ',' + self._props.margin.top + ')');

  // Area for data
  self._state.dataArea = {
    width: self._state.contentArea.width - self._props.margin.right,
    height: self._state.contentArea.height - self._props.margin.top - self._props.margin.bottom
  };

  self._elem.dataCanvas
    .attr('width', self._state.dataArea.width)
    .attr('height', self._state.dataArea.height);

  self._elem.dataClipper
    .attr('width', self._state.dataArea.width + 2)
    .attr('height', self._state.dataArea.height + 1);

  // Update pixel ranges for x and y dimension
  self._helpers.yScale.rangeRound([0, self._state.dataArea.height]);
  self._helpers.xScale.rangeRound([0, self._state.dataArea.width]);

  // Redraw everything basd on new x axis size
  self._resizeAxisX();
};

// Turn data input format into the internal format used in visualizing the data
TopHistogram.prototype._transformData = function (rawData) {
  var self = this;
  return rawData
};

// Render things that don't change when receiving the first data, updating the data or resizing the content area
TopHistogram.prototype._renderBase = function (container) {
  var self = this;

  self._elem.container = container;
  self._elem.container.classed('statistics-vis statistics-top-histogram', true);

  self._elem.title = self._elem.container.append('h3')
    .classed('statistics-vis-title', true)
    .text(self._texts.title);

  self._elem.svg = self._elem.container.append('svg');
  self._elem.svgCanvas = self._elem.svg.append('g')
    .attr('transform', 'translate(' + self._props.margin.left + ',' + self._props.margin.top + ')');

  self._elem.backLayer = self._elem.svgCanvas.append('g');

  self._elem.dataCanvas = self._elem.svgCanvas.append('g');

  // Cuts out the data elements outside the axes
  self._elem.dataClipper = self._elem.svgCanvas
    .append('clipPath')
      .attr('transform', 'translate(-1, -1)')
      .attr('id', 'js-statistics-top-histogram-data-clipper-' + self._props.id)
    .append('rect');

  self._elem.dataCanvas.attr('clip-path', 'url(#js-statistics-totals-timeline-data-clipper-' + self._props.id + ')');

  self._elem.histogramCanvas = self._elem.dataCanvas.append('g');

  self._elem.frontLayer = self._elem.svgCanvas.append('g');

  self._helpers.xScale = d3.scaleLinear();
  self._helpers.yScale = d3.scaleBand().padding(0.25);

  // X axis
  self._updateXAxisGenerator();

  self._elem.xAxis = self._elem.backLayer.append('g')
    .classed('statistics-axis', true)
    .call(self._helpers.xAxisGenerator);
  // No Y axis

  // Legend
  self._elem.legend = self._elem.frontLayer.append('g')
    .classed('statistics-legend', true)
    .attr('transform', 'translate(0, -90)');

  self._props.legend.forEach(function(rowData, i) {
    var row = self._elem.legend.append('g')
      .attr('transform', 'translate(0, ' + (i * 25) + ')')
      .classed('statistics-legend-row', true);

    row.append('rect')
      .classed('statistics-legend-coloring', true)
      .attr('height', 15)
      .attr('width', 15);

    row.append('text')
      .text(rowData.title)
      .classed('statistics-legend-text', true)
      .attr('transform', 'translate(25, 12.5)')
      .attr('width', self._state.dataArea.width - self._props.margin.left)
  });
};

// Draw or update the histogram bars with the given data
TopHistogram.prototype._renderHistogram = function (histogramData) {
  var self = this;

  var tooltip = d3.select('body')
    .append('div')
    .attr('class', 'statistics-tooltip')

  // Join new data
  self._elem.histogramBars = self._elem.histogramCanvas.selectAll('.statistics-bar')
    .data(histogramData, function(d) { return d ? d.name : this.id })
    .attr('transform', function(d, i) { return (
      'translate(0,' + self._helpers.yScale(d[self._schema.labelField]) + ')'
    )});

  self._elem.histogramBars.selectAll('.statistics-bar-main')
    .data(histogramData, function(d) { return d ? d.name : this.id })
    .attr('width', function (d) {
      return (
        d[self._schema.valueField] > 0 ? self._helpers.xScale(d[self._schema.valueField]) - 1 : self._helpers.xScale(d[self._schema.valueField])
      )
    })
    .on('mouseover', function(d) {
      tooltip.text(d.all);
      return tooltip.classed('active', true);
    })
    .on('mousemove', function() {
      return tooltip.style('top', (d3.event.pageY + 20) + 'px')
        .style('left',(d3.event.pageX + 10) + 'px');
    })
    .on('mouseout', function() {
      return tooltip.classed('active', false);
    });

  self._elem.histogramBars.selectAll('.statistics-bar-portion')
    .data(histogramData, function(d) { return d ? d.name : this.id })
    .attr('width', function (d) { return (
      self._helpers.xScale(d[self._schema.valueSpecificField])
    )})
    .on('mouseover', function(d) {
      tooltip.text(d.specific);
      return tooltip.classed('active', true);
    })
    .on('mousemove', function() {
      return tooltip.style('top', (d3.event.pageY + 20) + 'px')
        .style('left',(d3.event.pageX + 10) + 'px');
    })
    .on('mouseout', function() {
      return tooltip.classed('active', false);
    });

  function wrap(text, width, yMiddle) {
    width = width - 10;
    text.each(function() {
      var text = d3.select(this);
      var words = text.text().split(/\s+/).reverse();
      var word;
      var line = [];
      var lineNumber = 0;
      var lineHeight = 1.1; // ems
      var dy = 1.25;
      var tspan = text.text(null).append('tspan')
        .attr('x', -6)
        .attr('y', '0.25em')
        .attr('dy', dy + 'em');
      while (word = words.pop()) {
        line.push(word);
        tspan.text(line.join(" "));
        if (tspan.node().getComputedTextLength() > width) {
          line.pop();
          tspan.text(line.join(' '));
          line = [word];
          tspan = text.append('tspan')
            .attr('x', -6)
            .attr('dy', ++lineNumber * lineHeight + dy + 'em')
            .text(word);
          var offset = -0.2;
          if (lineNumber > 1) {
            offset = -0.75
          }
          text.selectAll('tspan').attr('y', offset + 'em')
        }
      }
    })
  }

  self._elem.histogramBars.selectAll('text')
    .data(histogramData, function(d) { return d ? d.name : this.id })
    .text(function(d, i) { return d[self._schema.labelField] })
    .call(wrap, self._props.margin.left, self._helpers.yScale.bandwidth() / 2);

  // Bars added to the previous data (added to the end)
  var barsToAdd = self._elem.histogramBars.enter().append('g')
    .classed('statistics-bar', true);

  // Main bar itself
  barsToAdd.append('rect')
    .classed('statistics-bar-main', true)
    .attr('x', 1)
    .attr('y', 1)
    .attr('height', self._helpers.yScale.bandwidth());

  // Shorter portion bar
  barsToAdd.append('rect')
    .classed('statistics-bar-portion', true)
    .attr('x', 1)
    .attr('y', 1)
    .attr('height', self._helpers.yScale.bandwidth());

  // Label text
  barsToAdd.append("svg:a").attr("xlink:href", function(d){ return self.getUrl(d.category) + d.id }).attr("target", "_blank")
  .append('text')
    .attr('x', -6)
    .attr('y', self._helpers.yScale.bandwidth() / 2)
    .attr('text-anchor', 'end')
    .attr('width', self._props.margin.left);

  // Bars from previous data to leave out
  var barsToRemove = self._elem.histogramBars.exit();
  barsToRemove.remove();
};


TopHistogram.prototype._resizeAxisX = function () {
  var self = this;
  if (!self._data.histogram) {
    return
  }
  var currentExtent = self._helpers.xScale.domain();
  var newExtent = self._getXExtent();

  clearTimeout(self._state.resizeAxisTimeout);
  self._state.resizeAxisTimeout = setTimeout(function () {
    self._elem.xAxis.transition().duration(800)
    .tween('resizeAxisTween', function (d, i) {
      var axisScaleInterpolator = d3.interpolate(currentExtent, newExtent);
      return function (t) {
        self._helpers.xScale.domain(axisScaleInterpolator(t));
        self._elem.xAxis.call(self._helpers.xAxisGenerator);
        self._renderHistogram(self._data.histogram)
      }
    })
    .on('end', function () {
      self._updateXAxisGenerator();
      self._elem.xAxis.call(self._helpers.xAxisGenerator)
    })
  }, 100)
};


TopHistogram.prototype._updateXAxisGenerator = function () {
  var self = this;
  self._helpers.xAxisGenerator = function (g) {
    // Around 5 reference lines. Use only integers.
    var tickCount = Math.min(4, self._helpers.xScale.domain().slice(-1)[0]);
    g.call(
      d3.axisTop(self._helpers.xScale)
      .tickSize(self._state.dataArea.width)
      .ticks(tickCount)
      .tickPadding(7)
      .tickFormat(function (d) {
        return this.parentNode.nextSibling ? '\xa0' + d : d + ' ' + self._texts.amount
      })
    );
    g.classed('statistics-axis', true);
    g.select('.domain').remove();
    g.selectAll('.tick');
    g.selectAll('.tick text')
      .attr('y', -5);
    g.selectAll('.tick line')
      .attr('y2', self._state.dataArea.height)
      .attr('height', self._state.dataArea.height)
  }
};


TopHistogram.prototype._getXExtent = function () {
  var self = this;
  return [0, Math.round(d3.max(self._data.histogram, function(d) { return d[self._schema.valueField]}) * 1.25 + 1)]
};
