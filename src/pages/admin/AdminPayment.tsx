import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { DashboardLayout } from "@/components/layout/DashboardLayout";
import { StatCard } from "@/components/dashboard/StatCard";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Badge } from "@/components/ui/badge";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { CreditCard, DollarSign, Search, MoreVertical, CheckCircle2, XCircle, Clock, TrendingUp, Eye, Download, RefreshCw, Send } from "lucide-react";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { useToast } from "@/components/ui/use-toast";

const initialPayments = [
  {
    id: "PAY001",
    student: "John Smith",
    studentId: "ST001",
    amount: 1250.00,
    type: "Tuition Fee",
    method: "Credit Card",
    date: "2024-01-15",
    status: "Paid",
    transactionId: "TXN-2024-001234",
  },
  {
    id: "PAY002",
    student: "Emily Johnson",
    studentId: "ST002",
    amount: 1250.00,
    type: "Tuition Fee",
    method: "Bank Transfer",
    date: "2024-01-14",
    status: "Paid",
    transactionId: "TXN-2024-001235",
  },
  {
    id: "PAY003",
    student: "Michael Brown",
    studentId: "ST003",
    amount: 500.00,
    type: "Library Fee",
    method: "Cash",
    date: "2024-01-13",
    status: "Paid",
    transactionId: "TXN-2024-001236",
  },
  {
    id: "PAY004",
    student: "Sarah Davis",
    studentId: "ST004",
    amount: 1250.00,
    type: "Tuition Fee",
    method: "Credit Card",
    date: "2024-01-12",
    status: "Pending",
    transactionId: "TXN-2024-001237",
  },
  {
    id: "PAY005",
    student: "David Wilson",
    studentId: "ST005",
    amount: 300.00,
    type: "Exam Fee",
    method: "Online Payment",
    date: "2024-01-11",
    status: "Paid",
    transactionId: "TXN-2024-001238",
  },
  {
    id: "PAY006",
    student: "Jessica Martinez",
    studentId: "ST006",
    amount: 1250.00,
    type: "Tuition Fee",
    method: "Bank Transfer",
    date: "2024-01-10",
    status: "Failed",
    transactionId: "TXN-2024-001239",
  },
];

export default function AdminPayment() {
  const navigate = useNavigate();
  const [payments, setPayments] = useState(initialPayments);
  const [searchQuery, setSearchQuery] = useState("");
  const [statusFilter, setStatusFilter] = useState("all");
  const [isAddDialogOpen, setIsAddDialogOpen] = useState(false);
  const [isDetailsDialogOpen, setIsDetailsDialogOpen] = useState(false);
  const [selectedPayment, setSelectedPayment] = useState<any>(null);
  const [formData, setFormData] = useState({
    student: "",
    studentId: "",
    amount: "",
    type: "",
    method: "",
    date: new Date().toISOString().split('T')[0],
  });
  const { toast } = useToast();

  const totalRevenue = payments.filter(p => p.status === "Paid").reduce((sum, p) => sum + p.amount, 0);
  const monthlyRevenue = payments
    .filter(p => p.status === "Paid" && new Date(p.date).getMonth() === new Date().getMonth())
    .reduce((sum, p) => sum + p.amount, 0);
  const pendingPayments = payments.filter(p => p.status === "Pending").length;

  const filteredPayments = payments.filter((payment) => {
    const matchesSearch = payment.student.toLowerCase().includes(searchQuery.toLowerCase()) ||
      payment.studentId.toLowerCase().includes(searchQuery.toLowerCase()) ||
      payment.transactionId.toLowerCase().includes(searchQuery.toLowerCase());
    const matchesStatus = statusFilter === "all" || payment.status.toLowerCase() === statusFilter.toLowerCase();
    return matchesSearch && matchesStatus;
  });

  const handleAddSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const newPayment = {
      id: `PAY${String(payments.length + 1).padStart(3, '0')}`,
      ...formData,
      amount: parseFloat(formData.amount),
      status: "Paid",
      transactionId: `TXN-${new Date().getFullYear()}-${String(Math.floor(Math.random() * 1000000)).padStart(6, '0')}`,
    };
    setPayments([...payments, newPayment]);
    toast({
      title: "Payment Recorded",
      description: `Payment of $${formData.amount} has been successfully recorded.`,
    });
    setIsAddDialogOpen(false);
    resetForm();
  };

  const handleViewDetails = (payment: any) => {
    setSelectedPayment(payment);
    setIsDetailsDialogOpen(true);
  };

  const handleDownloadReceipt = (payment: any) => {
    toast({
      title: "Downloading Receipt",
      description: `Receipt for ${payment.transactionId} is being downloaded...`,
    });
    // In a real app, this would trigger a download
  };

  const handleRefund = (payment: any) => {
    const updatedPayments = payments.map((p) =>
      p.id === payment.id ? { ...p, status: "Refunded" } : p
    );
    setPayments(updatedPayments);
    toast({
      title: "Refund Processed",
      description: `Refund of $${payment.amount} has been processed for ${payment.student}.`,
    });
  };

  const handleResendReceipt = (payment: any) => {
    toast({
      title: "Receipt Sent",
      description: `Receipt has been sent to ${payment.student}'s email.`,
    });
  };

  const resetForm = () => {
    setFormData({
      student: "",
      studentId: "",
      amount: "",
      type: "",
      method: "",
      date: new Date().toISOString().split('T')[0],
    });
  };

  return (
    <DashboardLayout title="Payment" userRole="admin">
      {/* Stats Row */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <StatCard
          icon={<DollarSign className="w-6 h-6 text-green-600" />}
          iconBgColor="bg-green-100"
          label="Total Revenue"
          value={`$${totalRevenue.toLocaleString()}`}
        />
        <StatCard
          icon={<TrendingUp className="w-6 h-6 text-blue-600" />}
          iconBgColor="bg-blue-100"
          label="This Month"
          value={`$${monthlyRevenue.toLocaleString()}`}
        />
        <StatCard
          icon={<Clock className="w-6 h-6 text-amber-600" />}
          iconBgColor="bg-amber-100"
          label="Pending"
          value={pendingPayments}
        />
      </div>

      {/* Main Content */}
      <Card>
        <CardHeader>
          <div className="flex items-center justify-between">
            <div>
              <CardTitle>Payment Management</CardTitle>
              <p className="text-sm text-muted-foreground mt-1">
                Track and manage all payment transactions
              </p>
            </div>
            <div className="flex items-center gap-3">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <Input
                  placeholder="Search payments..."
                  className="pl-10 w-64"
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                />
              </div>
              <Select value={statusFilter} onValueChange={setStatusFilter}>
                <SelectTrigger className="w-40">
                  <SelectValue placeholder="Filter" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="all">All Status</SelectItem>
                  <SelectItem value="paid">Paid</SelectItem>
                  <SelectItem value="pending">Pending</SelectItem>
                  <SelectItem value="failed">Failed</SelectItem>
                </SelectContent>
              </Select>
              <Button onClick={() => setIsAddDialogOpen(true)}>
                <CreditCard className="w-4 h-4 mr-2" />
                Record Payment
              </Button>
            </div>
          </div>
        </CardHeader>
        <CardContent>
          <div className="rounded-md border">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Payment ID</TableHead>
                  <TableHead>Student</TableHead>
                  <TableHead>Type</TableHead>
                  <TableHead>Amount</TableHead>
                  <TableHead>Method</TableHead>
                  <TableHead>Date</TableHead>
                  <TableHead>Transaction ID</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead className="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {filteredPayments.map((payment) => (
                  <TableRow key={payment.id}>
                    <TableCell className="font-mono text-sm">{payment.id}</TableCell>
                    <TableCell>
                      <div>
                        <div className="font-medium">{payment.student}</div>
                        <div className="text-sm text-muted-foreground">
                          {payment.studentId}
                        </div>
                      </div>
                    </TableCell>
                    <TableCell>
                      <Badge variant="outline">{payment.type}</Badge>
                    </TableCell>
                    <TableCell>
                      <div className="font-semibold text-green-600">
                        ${payment.amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                      </div>
                    </TableCell>
                    <TableCell>
                      <div className="flex items-center gap-2">
                        <CreditCard className="w-4 h-4 text-muted-foreground" />
                        <span className="text-sm">{payment.method}</span>
                      </div>
                    </TableCell>
                    <TableCell>{payment.date}</TableCell>
                    <TableCell>
                      <span className="font-mono text-xs text-muted-foreground">
                        {payment.transactionId}
                      </span>
                    </TableCell>
                    <TableCell>
                      <Badge
                        variant={
                          payment.status === "Paid"
                            ? "default"
                            : payment.status === "Pending"
                            ? "secondary"
                            : "destructive"
                        }
                        className={
                          payment.status === "Paid"
                            ? "bg-green-500"
                            : payment.status === "Pending"
                            ? "bg-amber-500"
                            : "bg-red-500"
                        }
                      >
                        <div className="flex items-center gap-1">
                          {payment.status === "Paid" && <CheckCircle2 className="w-3 h-3" />}
                          {payment.status === "Failed" && <XCircle className="w-3 h-3" />}
                          {payment.status === "Pending" && <Clock className="w-3 h-3" />}
                          {payment.status}
                        </div>
                      </Badge>
                    </TableCell>
                    <TableCell className="text-right">
                      <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                          <Button variant="ghost" size="icon">
                            <MoreVertical className="w-4 h-4" />
                          </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                          <DropdownMenuItem onClick={() => handleViewDetails(payment)}>
                            <Eye className="w-4 h-4 mr-2" />
                            View Details
                          </DropdownMenuItem>
                          <DropdownMenuItem onClick={() => handleDownloadReceipt(payment)}>
                            <Download className="w-4 h-4 mr-2" />
                            Download Receipt
                          </DropdownMenuItem>
                          {payment.status === "Paid" && (
                            <DropdownMenuItem onClick={() => handleRefund(payment)}>
                              <RefreshCw className="w-4 h-4 mr-2" />
                              Refund
                            </DropdownMenuItem>
                          )}
                          <DropdownMenuItem onClick={() => handleResendReceipt(payment)}>
                            <Send className="w-4 h-4 mr-2" />
                            Resend Receipt
                          </DropdownMenuItem>
                        </DropdownMenuContent>
                      </DropdownMenu>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>
        </CardContent>
      </Card>

      {/* Record Payment Dialog */}
      <Dialog open={isAddDialogOpen} onOpenChange={setIsAddDialogOpen}>
        <DialogContent className="max-w-2xl">
          <DialogHeader>
            <DialogTitle>Record Payment</DialogTitle>
            <DialogDescription>
              Enter payment details to record a new transaction.
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={handleAddSubmit}>
            <div className="grid gap-4 py-4">
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="student">Student Name *</Label>
                  <Input
                    id="student"
                    required
                    value={formData.student}
                    onChange={(e) => setFormData({ ...formData, student: e.target.value })}
                    placeholder="John Smith"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="studentId">Student ID *</Label>
                  <Input
                    id="studentId"
                    required
                    value={formData.studentId}
                    onChange={(e) => setFormData({ ...formData, studentId: e.target.value })}
                    placeholder="ST001"
                  />
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="amount">Amount *</Label>
                  <Input
                    id="amount"
                    type="number"
                    step="0.01"
                    required
                    value={formData.amount}
                    onChange={(e) => setFormData({ ...formData, amount: e.target.value })}
                    placeholder="1250.00"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="type">Payment Type *</Label>
                  <Select
                    value={formData.type}
                    onValueChange={(value) => setFormData({ ...formData, type: value })}
                    required
                  >
                    <SelectTrigger id="type">
                      <SelectValue placeholder="Select type" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Tuition Fee">Tuition Fee</SelectItem>
                      <SelectItem value="Library Fee">Library Fee</SelectItem>
                      <SelectItem value="Exam Fee">Exam Fee</SelectItem>
                      <SelectItem value="Other">Other</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="method">Payment Method *</Label>
                  <Select
                    value={formData.method}
                    onValueChange={(value) => setFormData({ ...formData, method: value })}
                    required
                  >
                    <SelectTrigger id="method">
                      <SelectValue placeholder="Select method" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Credit Card">Credit Card</SelectItem>
                      <SelectItem value="Bank Transfer">Bank Transfer</SelectItem>
                      <SelectItem value="Cash">Cash</SelectItem>
                      <SelectItem value="Online Payment">Online Payment</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="date">Payment Date *</Label>
                  <Input
                    id="date"
                    type="date"
                    required
                    value={formData.date}
                    onChange={(e) => setFormData({ ...formData, date: e.target.value })}
                  />
                </div>
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setIsAddDialogOpen(false)}>
                Cancel
              </Button>
              <Button type="submit">
                <CreditCard className="w-4 h-4 mr-2" />
                Record Payment
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Payment Details Dialog */}
      <Dialog open={isDetailsDialogOpen} onOpenChange={setIsDetailsDialogOpen}>
        <DialogContent className="max-w-2xl">
          <DialogHeader>
            <DialogTitle className="flex items-center gap-2">
              <CreditCard className="w-5 h-5" />
              Payment Details
            </DialogTitle>
            <DialogDescription>
              Complete payment transaction information
            </DialogDescription>
          </DialogHeader>
          <div className="py-4">
            {selectedPayment && (
              <div className="space-y-4">
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <p className="text-sm text-muted-foreground">Payment ID</p>
                    <p className="font-mono font-medium">{selectedPayment.id}</p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Transaction ID</p>
                    <p className="font-mono font-medium">{selectedPayment.transactionId}</p>
                  </div>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <p className="text-sm text-muted-foreground">Student</p>
                    <p className="font-medium">{selectedPayment.student}</p>
                    <p className="text-sm text-muted-foreground">{selectedPayment.studentId}</p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Amount</p>
                    <p className="text-2xl font-bold text-green-600">
                      ${selectedPayment.amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                    </p>
                  </div>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <p className="text-sm text-muted-foreground">Payment Type</p>
                    <Badge variant="outline">{selectedPayment.type}</Badge>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Payment Method</p>
                    <Badge variant="secondary">{selectedPayment.method}</Badge>
                  </div>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <p className="text-sm text-muted-foreground">Date</p>
                    <p className="font-medium">{selectedPayment.date}</p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Status</p>
                    <Badge
                      variant={
                        selectedPayment.status === "Paid"
                          ? "default"
                          : selectedPayment.status === "Pending"
                          ? "secondary"
                          : "destructive"
                      }
                      className={
                        selectedPayment.status === "Paid"
                          ? "bg-green-500"
                          : selectedPayment.status === "Pending"
                          ? "bg-amber-500"
                          : "bg-red-500"
                      }
                    >
                      {selectedPayment.status}
                    </Badge>
                  </div>
                </div>
              </div>
            )}
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setIsDetailsDialogOpen(false)}>
              Close
            </Button>
            {selectedPayment && (
              <>
                <Button variant="outline" onClick={() => handleDownloadReceipt(selectedPayment)}>
                  <Download className="w-4 h-4 mr-2" />
                  Download Receipt
                </Button>
                <Button onClick={() => {
                  setIsDetailsDialogOpen(false);
                  navigate(`/admin/students/${selectedPayment.studentId}`);
                }}>
                  View Student
                </Button>
              </>
            )}
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </DashboardLayout>
  );
}

