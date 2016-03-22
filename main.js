'use strict';

var TableContent = React.createClass({
	render: function() {
		var row = this.props.row;
		var timeStamp = moment(row.timeStamp).format('MMM D, YYYY, h:mm:ss a');
		return (
			<tr data-id={row.id}>
				<td>{row.area}</td>
				<td>{row.state}</td>
				<td>{timeStamp}</td>
				<td><button className="btn btn-danger btn-xs" onClick={this.props.delete} data-id={row.id}>X</button></td>
			</tr>
		);
	}
});

var App = React.createClass({
	getInitialState: function() {
		return {
			rows: [],
			displayDate: null
		};
	},
	componentDidMount: function() {
		this.fetchData();
		setInterval(this.fetchData, 2000);
	},
	fetchData: function() {
		fetch('./store.php').then(function(response) {
			return response.json();
		}).then(function(j) {
			this.setState({
				rows: j
			});
		}.bind(this));
	},
	delete: function(e) {
		var dataId = e.target.getAttribute('data-id');
		fetch('./store.php?action=delete&id=' + dataId).then(function(response) {
			return response.json();
		}).then(function(j) {
			this.setState({
				rows: j
			});
		}.bind(this));
	},
	render: function() {
		return (
			<table className="table table-striped table-bordered table-condensed">
				<thead>
					<tr>
						<th>Area</th>
						<th>State</th>
						<th>Time</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>
				{this.state.rows.map(function(row, i) {
					return <TableContent key={i} row={row} delete={this.delete}/>
				}.bind(this))}
				</tbody>
			</table>
		);
	}
});

ReactDOM.render(<App />,
	document.querySelector('.app')
);
